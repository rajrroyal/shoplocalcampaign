<?php

namespace App\Services\Shopify;

use App\Models\ErrorLog;
use App\Models\Store;
use App\Services\Utilities\AlertService;
use App\Services\Utilities\JsonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ShopifyAuthorizationService
{

    /**
     * Validate data returned to oAuth callback
     *
     * @param  Request  $request
     * @return array|null
     */
    public function confirm(Request $request)
    {
        // Validate querystring data returned from Shopify
        $error = '';
        if (! strlen($error)) { $error = $this->nonceError($request); }
        if (! strlen($error)) { $error = $this->hmacError($request); }
        if (! strlen($error)) { $error = $this->hostnameError($request); }

        if (! strlen($error)) {

            return (object) $request->input();
        }

        ErrorLog::create([
            'event' => 'Shopify validation error',
            'message' => $error
        ]);

        return null;
    }

    /**
     * Exchange access code for permanent access token
     *
     * @param  Request  $request
     * @param  int  $userId
     * @return |null
     */
    public function requestTokenUpgrade($confirmation)
    {
        $shop = $confirmation->shop;
        $code = $confirmation->code;

        $upgradeUrl = 'https://' . $shop . '/admin/oauth/access_token';

        $response = Http::post($upgradeUrl, [
            'client_id' => config('shopify.api_key'),
            'client_secret' => config('shopify.api_secret'),
            'code' => $code
        ]);

        $data = (new JsonService())->decode($response->body(), JsonService::OBJECT_FORMAT);

        if ($data->success && $data->data && $data->data->access_token) {

            return $data->data->access_token;
        }

        ErrorLog::create([
            'event' => 'Shopify access_token request',
            'data' => json_encode([
                'confirmation' => $confirmation,
                'data' => $data,
                'response_body' => $response->body(),
                'response_status' => $response->status()
            ])
        ]);

        return null;
    }

    // ---------------------------------------------------------------------

    /**
     * Verify that the nonce value generated before requesting access matches
     * the one returned by Shopify
     *
     * @param  Request  $request
     * @return string|null  Error message
     */
    protected function nonceError(Request $request)
    {
        $nonceSent = session()->get('shopify_nonce');
        $nonceReceived = $request->input('state');

        return (($nonceSent === $nonceReceived) ? null : 'Invalid nonce: (expected: '.$nonceSent.', received: '.$nonceReceived.')');
    }

    /**
     * Verify that the parameters returned by Shopify have not been corrupted
     *
     * @param  Request  $request
     * @return string|null  Error message
     */
    protected function hmacError(Request $request)
    {
        // Get the hmac passed from Shopify
        $hmac = $request->input('hmac', '');

        // Get the other querystring parameters, not including hmac
        $querystringArray = $request->query();
        unset($querystringArray['hmac']);
        $querystring = http_build_query($querystringArray);

        // SHA256-Hash the remaining querystring, using the api secret as the key
        $apiSecret = config('shopify.api_secret');
        $hexdigest = hash_hmac('sha256', $querystring, $apiSecret);

        // Verify hash against hmac sent by Shopify
        return (($hmac === $hexdigest) ? null : 'Invalid hmac (expected: '.$hexdigest.', received: '.$hmac.')');
    }

    /**
     * Verify that the hostname returned by Shopfiy is a valid url
     *
     * @param  Request  $request
     * @return string|null  Error message
     */
    protected function hostnameError(Request $request)
    {
        $hostname = $request->input('shop', '');
        $pattern = '/((https|http)\:\/\/)?[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com[\/]?/';

        return ((preg_match($pattern, $hostname)) ? null : 'Invalid hostname: '.$hostname);
    }

    // ---------------------------------------------------------------------

    /**
     * Return a redirect to the Shopify oAuth Authentication page
     *
     * @param  string  $shop
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector

    // MOVED TO FRONT-END

    public function oAuthRedirect(string $shop)
    {
    // Unique identifier
    $nonce = Str::random(20);
    session()->put('shopify_nonce', $nonce);

    // Generate URL
    $url = 'https://' . $shop . '.myshopify.com/admin/oauth/authorize'
    . '?client_id=' . config('shopify.api_key')
    . '&scope=' . config('shopify.api_scope')
    . '&state=' . $nonce
    . '&redirect_uri=' . route('account-connect-shopify-authorize.confirm');

    return $url;
    }
     */
}
