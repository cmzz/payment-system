<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderPreOrderedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chargeId;
    public $preOrderData;

    /**
     * Create a new event instance.
     *
     * @param int $chargeId
     * @param array $preOrderData
     */
    public function __construct(int $chargeId, array $preOrderData)
    {
        $this->chargeId = $chargeId;
        $this->preOrderData = $preOrderData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
