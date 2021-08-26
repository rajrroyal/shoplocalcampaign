<?php

namespace App\Services\Shopcity;

use App\Models\ErrorLog;
use App\Models\Product;
use App\Models\Shopcity\ShopcityDirectory;
use App\Models\Shopcity\ShopcityFolder;
use App\Models\Shopcity\ShopcityListing;
use App\Models\Shopcity\ShopcityMicroproduct;
use App\Models\Store;
use App\Services\CacheService;
use Illuminate\Filesystem\Cache;

class ShopcityUpdateListingService
{
    public function updateListing(Store $store)
    {
        if ($listing = $this->getListingFromFolder($store->source_url)) {

            $store->update([
                'source_ref_id' => $listing->listingid,
                'name' => trim($listing->businessname),
                'url' => $store->source_url,
                'address1' => $listing->address1,
                'address2' => $listing->address2,
                'city' => $listing->city,
                'province' => $listing->province,
                'country' => $listing->country,
                'postalcode' => $listing->postal,
                'latitude' => $listing->lat3,
                'longitude' => $listing->lng3,
                'phone' => $listing->phone,
                'email' => $listing->email,
                'raw_json' => json_encode($listing)
            ]);

            // Update logo
            try {
                $store
                    ->addMediaFromUrl('https://secure.shopcity.com/microsite/photos/'.$listing->microsite->micrositeid.'-01.jpg')
                    ->toMediaCollection('logo');

            } catch(\Exception $e) {
                // Ignore
            }

            // Clear cache
            (new CacheService())->clearStoreCache($store->user_id);
        }

        return $listing;
    }

    // ---------------------------------------------------------------------

    protected function getListingFromFolder(string $sourceUrl)
    {
        if ($urlParts = $this->parseShopcityUrl($sourceUrl)) {

            if ($directory = $this->getDirectory($urlParts->domain)) {

                $listing = ShopcityFolder
                    ::with([
                        'listing',
                        'listing.microproducts',
                        'listing.microproducts.category',
                        'listing.microproducts.images',
                        'listing.microproducts.microcatsToProduct',
                        'listing.microproducts.microcatsToProduct.microcat'
                    ])
                    ->where('folder', $urlParts->folder)
                    ->where('directoryid', $directory->directoryid)
                    ->where('status', 1000)
                    ->where('ftype', 1)
                    ->first();

                if ($listing) {
                    return $listing->listing;
                }
            }
        }

        return null;
    }

    protected function parseShopcityUrl(string $sourceUrl)
    {
        $sourceUrl = str_ireplace('https://', '', $sourceUrl);
        $sourceUrl = str_ireplace('http://', '', $sourceUrl);
        $sourceUrl = str_ireplace('www.', '', $sourceUrl);

        $parts = explode('/', $sourceUrl);
        $domain = $parts[0];
        $folder = sizeof($parts) > 1 ? $parts[1] : null;

        if (strlen($domain) && strlen($folder)) {

            return (object) [
                'domain' => $domain,
                'folder' => $folder
            ];
        }

        ErrorLog::create([
            'event' => 'Parsing Shopcity source URL',
            'data' => json_encode([
                'source_url' => $sourceUrl
            ])
        ]);

        return null;
    }

    protected function getDirectory(string $domain)
    {
        $directory = ShopcityDirectory
            ::where(function($query) use($domain) {
                $query->where('siteurl', $domain);
                $query->orWhere('alturl', $domain);
            })
            ->first();

        if (! $directory) {
            ErrorLog::create([
                'event' => 'Shopcity directory lookup',
                'data' => json_encode([
                    'domain' => $domain
                ])
            ]);
        }

        return $directory;
    }
}
