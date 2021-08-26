<?php

namespace App\Services\Ecwid;

use App\Models\Store;
use App\Services\CacheService;
use App\Services\GeoLookup;
use App\Services\Utilities\JsonService;
use Illuminate\Support\Facades\Http;

class EcwidUpdateStoreService
{
    public function update(Store $store)
    {
        $requestUrl = 'https://app.ecwid.com/api/v3/' . $store->source_url . '/profile'
            . '?token=' . $store->source_access_token;

        $response = Http::get($requestUrl);

        $data = (new JsonService())->decode($response, JsonService::OBJECT_FORMAT);

        if ($data->success) {

            $data = $data->data;

            $input = [
                'raw_json' => json_encode($data)
            ];

            if (property_exists($data, 'generalInfo')) {
                $input = array_merge($input, [
                    'source_ref_id' => $data->generalInfo->storeId,
                    'url' => $data->generalInfo->storeUrl
                ]);
            }

            if (property_exists($data, 'company')) {
                // The Ecwid data for street can contain linebreaks so normalize it first
                $streetAddress = $this->normalizeStreet($data->company->street);

                $input = array_merge($input, [
                    'name' => $data->company->companyName,
                    'email' => $data->company->email,
                    'address1' => $streetAddress->address1,
                    'address2' => $streetAddress->address2,
                    'city' => $data->company->city,
                    'country' => $data->company->countryCode,
                    'postalcode' => $data->company->postalCode,
                    'province' => $data->company->stateOrProvinceCode,
                    'phone' => $data->company->phone
                ]);


                // Look up latitude and longitude of address
                $coordinates = (new GeoLookup())->lookupAddress(
                    $data->company->street,
                    $data->company->city,
                    $data->company->stateOrProvinceCode,
                    $data->company->countryCode
                );

                if ($coordinates->latitude || $coordinates->longitude) {
                    $input = array_merge($input, [
                        'latitude' => $coordinates->latitude,
                        'longitude' => $coordinates->longitude
                    ]);
                }
            }

            // Update store logo
            if (property_exists($data, 'instantSiteInfo')) {
                $store->addMediaFromUrl($data->instantSiteInfo->storeLogoUrl)
                    ->toMediaCollection('logo');
            }

            // Update store info
            if (sizeof($input)) {
                $store->update($input);
                (new CacheService())->clearStoreCache($store->user_id);
            }
        }
    }

    // ---------------------------------------------------------------------

    protected function normalizeStreet(string $street)
    {
        $address = explode(PHP_EOL, $street);

        return (object) [
            'address1' => sizeof($address) >= 1 ? $address[0] : null,
            'address2' => sizeof($address) >= 2 ? $address[1] : null,
        ];
    }
}
