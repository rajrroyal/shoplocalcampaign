<?php

namespace App\Events;

use App\Models\Store;
use App\Services\CacheService;
use App\Services\StoreService;
use App\Transformers\StoreTileTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastUpdateComplete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $userId;
    public $store;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Store $store)
    {
        $this->userId = $store->user_id;

        (new CacheService())->clearProductCache($store->id);
        $store = Store::where('id', $store->id)->first();
        
        $this->store = (new StoreService())->transformStore($store);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.update.'.$this->userId);
    }

    public function broadcastAs()
    {
        return 'update.complete';
    }
}
