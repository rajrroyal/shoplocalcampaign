<?php

namespace App\Http\Controllers\Account\Shopcity;

use App\Helpers\UrlHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Connect\ShopcityCreateRequest;
use App\Jobs\UpdateShopcityStoreJob;
use App\Models\Store;
use App\Services\CacheService;
use App\Services\Utilities\AlertService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShopcitySetupController extends Controller
{
    public function create()
    {
        return view('account.connect.shopcity.authorize');
    }

    public function store(ShopcityCreateRequest $request)
    {
        $profileUrl = (new UrlHelper())->prepareUrl($request->profile);

        $store = Store::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'source' => 'shopcity',
                'source_url' => $profileUrl
            ],
            [
                'name' => $profileUrl,
                'update_started_at'=>Carbon::now()
            ]
        );

        (new CacheService())->clearStoreCache(auth()->id());
        $store = Store::where('id', $store->id)->first();

        // Update shop and product information in queued job
        UpdateShopcityStoreJob::dispatch($store);

        (new AlertService())->success($store->name . ' is updating now...');
        return redirect(route('account-dashboard.index'));
    }
}

// controller.plain.stub
