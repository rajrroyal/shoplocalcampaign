<?php

namespace App\Services\Shopify;

use App\Models\ErrorLog;
use App\Models\Store;
use App\Services\CacheService;
use App\Services\StoreService;
use App\Services\Utilities\JsonService;
use Illuminate\Support\Facades\Http;

class ShopifyUpdateShopService {

    public function updateShop(Store $store)
    {
        // Shopify API URL
        $url = 'https://'
            . config('shopify.api_key') . ':' . config('shopify.api_secret')
            . '@' . $store->source_url . '/admin/api/' . config('shopify.api_version')
            . '/shop.json';

        // Get response from Shopify
        $response = Http
            ::withHeaders([
                'X-Shopify-Access-Token' => $store->source_access_token
            ])
            ->get($url);

        // Decode JSON
        $data = (new JsonService())->decode($response);

        if ($data && $data->success && property_exists($data, 'data')) {

            $shop = $data->data->shop;

            $store->update([
                'source_ref_id' => $shop->id,
                'name' => $shop->name,
                'url' => ($shop->force_ssl ? 'https://':'http://') . $shop->domain,
                'address1' => $shop->address1,
                'address2' => $shop->address2,
                'city' => $shop->city,
                'province' => $shop->province,
                'country' => $shop->country,
                'postalcode' => $shop->zip,
                'latitude' => (float) $shop->latitude,
                'longitude' => (float) $shop->longitude,

                'phone' => $shop->phone,
                'email' => $shop->email,

                'raw_json' => json_encode($shop)
            ]);

            // Clear affected caches
            (new CacheService())->clearStoreCache($store->user_id);

            return true;
        }

        ErrorLog::create([
            'event' => 'Fetching Shopify Shop',
            'data' => json_encode([
                'store' => $store,
                'url' => $url,
                'data' => $data,
                'response_body' => $response->body(),
                'response_status' => $response->status()
            ])
        ]);

        return false;
    }
}
