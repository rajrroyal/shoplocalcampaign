<?php

namespace App\Events;

use App\Models\Store;
use App\Services\StoreService;
use App\Transformers\StoreTileTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastUpdateProgress implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $userId;
    public $store;
    public $value;
    public $maxValue;
    public $percent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Store $store, int $value = null, int $maxValue = null)
    {
        $this->userId = $store->user_id;
        $this->store = (new StoreService())->transformStore($store);

        $this->value = $value;
        $this->maxValue = $maxValue;
        $this->percent = ($maxValue) ? $value / $maxValue * 100 : 0;
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
        return 'update.progress';
    }
}
