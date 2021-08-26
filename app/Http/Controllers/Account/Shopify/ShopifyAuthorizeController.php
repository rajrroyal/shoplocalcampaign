<?php

namespace App\Http\Controllers\Account\Shopify;

use App\Helpers\UrlHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Connect\ShopifyCreateRequest;
use App\Jobs\UpdateShopifyStoreJob;
use App\Models\Store;
use App\Services\CacheService;
use App\Services\Shopify\ShopifyAuthorizationService;
use App\Services\Shopify\ShopifyUpdateShopService;
use App\Services\StoreService;
use App\Services\Utilities\AlertService;
use App\Services\Utilities\DebugbarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopifyAuthorizeController extends Controller
{
    /**
     * Form requesting user's Shopify store name
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Unique identifier
        $nonce = Str::random(20);
        session()->put('shopify_nonce', $nonce);

        $shop = '';
        if ($request->has('shop')) {
            $shop = str_replace('.myshopify.com', '', $request->shop);
        }

        $data = [
            'shop' => $shop,
            'client_id' => config('shopify.api_key'),
            'scope' => config('shopify.api_scope'),
            'state' => $nonce,
            'redirect_uri' => route('account-connect-shopify-authorize.confirm')
        ];

        (new DebugbarService())->dump($data);

        return view('account.connect.shopify.authorize', $data);
    }

    /**
     * Shopify If your app has been granted access to customers or orders, then you receive a data request webhook
     * with the resource IDs of the data that you need to provide to the store owner.
     * It's your responsibility to provide this data to the store owner directly.
     * In some cases, a customer record contains only the customer's email address.
     *
     * @return string
     */
    public function customers_data_request(): string
    {
        // @TODO
        return 'success';
    }

    /**
     * Shopify If your app has been granted access to the store's customers or orders,
     * then you receive a redaction request webhook with the resource IDs that you need to redact or delete.
     * In some cases, a customer record contains only the customer's email address.
     *
     * @return string
     */
    public function customers_redact(): string
    {
        // @TODO
        return 'success';
    }

    /**
     * Shopify erase the customer information for that store from your database
     *
     * @return string
     */
    public function shops_redact(Request $request): string
    {
        $myshopify_domain = $request->myshopify_domain;

        $store = Store::where('source_url', '=', $myshopify_domain)->first();

        if ($store) {
            $store->delete();
        }

        return 'success';
    }

    /**
     * Return a redirect to the Shopify oAuth Authentication page
     *
     */
    public function oAuthRedirect(Request $request)
    {
        $shop = $request->shop;

        // Unique identifier
        $nonce = Str::random(20);
        session()->put('shopify_nonce', $nonce);


        // Generate URL
        $url = 'https://' . $shop . '/admin/oauth/authorize'
            . '?client_id=' . config('shopify.api_key')
            . '&scope=' . config('shopify.api_scope')
            . '&state=' . $nonce
            . '&redirect_uri=' . route('account-connect-shopify-authorize.confirm');

        return redirect($url);
    }


    /**
     * Callback for Shopify oauth authorize page
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function confirm(Request $request)
    {
        $shopifyAuthorizationService = new ShopifyAuthorizationService();
        $alertService = new AlertService();

        // Validate the information returned from Shopify
        $confirmation = $shopifyAuthorizationService->confirm($request, auth()->id());

        if ($confirmation) {

            // Request token upgrade
            $permanentAccessToken = $shopifyAuthorizationService->requestTokenUpgrade($confirmation);

            if ($permanentAccessToken) {

                // Create new store record
                $store = Store::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'source' => 'shopify',
                        'source_url' => $confirmation->shop
                    ],
                    [
                        'name' => $confirmation->shop,
                        'source_access_token' => $permanentAccessToken,
                        'update_started_at' => Carbon::now()
                    ]
                );

                (new CacheService())->clearStoreCache(auth()->id());
                $store = Store::where('id', $store->id)->first();

                // Update shop and product information in queued job
                UpdateShopifyStoreJob::dispatch($store);

                $alertService->success($store->name . ' is updating now...');

            } else {

                $alertService->error('Sorry, there was a problem authenticating the selected store');
            }

        } else {

            $alertService->error('Sorry, there was a problem authenticating the selected store');
        }

        return view('account.connect.shopify.callback');
    }
}

// controller.plain.stub
