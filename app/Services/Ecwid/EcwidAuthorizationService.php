<?php

namespace App\Services\Ecwid;

use App\Jobs\UpdateEcwidStoreJob;
use App\Models\ErrorLog;
use App\Models\Store;
use App\Services\CacheService;
use App\Services\Utilities\AlertService;
use App\Services\Utilities\JsonService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EcwidAuthorizationService
{
    /**
     * Return Ecwid oAuth redirect url
     * GET https://my.ecwid.com/api/oauth/authorize?client_id={client_id}&redirect_uri={redirect_uri}&response_type=code&scope={scope}
     */
    public function oAuthRedirect()
    {
        return 'https://my.ecwid.com/api/oauth/authorize?' . implode('&', [
                'client_id=' . config('ecwid.client_id'),
                'redirect_uri=' . config('ecwid.confirm_uri'),
                'response_type=code',
                'scope=' . config('ecwid.scope')
            ]);
    }

    public function confirm(Request $request)
    {
        $alertService = (new AlertService());

        if ($request->has('code')) {

            $code = $request->code;

            $tokenData = $this->requestPermanentAccessToken($code);

            if ($tokenData) {

                // Create new store record
                $store = Store::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'source' => 'ecwid',
                        'source_url' => $tokenData->store_id
                    ],
                    [
                        'name' => 'New PowerShop Store',
                        'source_access_token' => $tokenData->access_token,
                        'update_started_at' => Carbon::now()
                    ]
                );

                (new CacheService())->clearStoreCache(auth()->id());
                $store = Store::where('id', $store->id)->first();

                // Update shop and product information in queued job
                UpdateEcwidStoreJob::dispatch($store);

                return $store;
            }

        } else {

            ErrorLog::create([
                'event' => 'Ecwid authorization attempt',
                'message' => $request->input('error', 'No error returned'),
                'data' => json_encode([
                    'input' => $request->input()
                ])
            ]);
        }

        return null;
    }

    // ---------------------------------------------------------------------

    protected function requestPermanentAccessToken(string $code)
    {
        $upgradeUrl = 'https://my.ecwid.com/api/oauth/token?' . implode('&', [
                'client_id=' . config('ecwid.client_id'),
                'client_secret=' . config('ecwid.client_secret'),
                'code=' . $code,
                'redirect_uri=' . config('ecwid.confirm_uri'),
                'grant_type=authorization_code'
            ]);

        $response = Http::get($upgradeUrl);

        $data = (new JsonService())->decode($response->body(), JsonService::OBJECT_FORMAT);

        if ($data->success && $data->data && property_exists($data->data, 'access_token')) {

            return $data->data;
        }

        $errorMessage = null;
        $errorDescription = null;

        if (property_exists($data, 'data') && property_exists($data->data, 'error')) {
            $errorMessage = $data->data->error;
            $errorDescription = $data->data->error_description;
        }

        ErrorLog::create([
            'event' => 'Ecwid access_token request',
            'message' => $errorMessage,
            'data' => json_encode([
                'error_description' => $errorDescription,
                'upgrade_url' => $upgradeUrl,
                'user_id' => auth()->id(),
                'code' => $code,
                'data' => $data,
                'response_body' => $response->body(),
                'response_status' => $response->status()
            ])
        ]);

        return null;
    }
}
