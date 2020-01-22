<?php

namespace App\Listeners;

use App\Stock;
use App\Product;
use App\Purchase;
use App\ProductStock;
use App\Events\StockPurchased;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStockInformation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Purchased  $event
     * @return void
     */
    public function handle(StockPurchased $event) : void
    {
        $stock = Stock::updateOrCreate([
            'hub_id' => $event->purchase->hub_id, 
            'user_type' => get_class($event->purchase),
            'user_id' => $event->purchase->id,
        ],[
            'product_id' => $event->purchase->id, 
            'quantity' => $event->purchase->quantity, 
            'unit' => $event->product->unit,
            'stock_status' => 'purchased',
            'status' => true
        ]);

        $this->updateProductStock($event->product , $stock);


    }

    public function updateProductStock($product , $stock) : void
    {
        $product_stock = ProductStock::where('product_id' , $product->id)->first();

        if(!isset($product_stock)){
            $product_stock = new ProductStock();
            $product_stock->product_id = $product->id;
            $product_stock->unit = $product->unit;
            $product_stock->in_stock = true;
            $product_stock->hub_id = $stock->quantity;
        } else{
            $product_stock->quantity = $product_stock->quantity + $stock->quantity;
        }

        $product_stock->save();

        
    }
}
