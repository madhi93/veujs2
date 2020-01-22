<?php

namespace App\Events;

use App\Product;
use App\Purchase;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockPurchased
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchase;
    public $product;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Purchase $purchase, Product $product)
    {
        $this->purchase = $purchase;
        $this->product = $product;
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
