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

    public $rechargeId;
    public $preOrderData;

    /**
     * Create a new event instance.
     *
     * @param int $rechargeId
     * @param array $preOrderData
     */
    public function __construct(int $rechargeId, array $preOrderData)
    {
        $this->rechargeId = $rechargeId;
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
