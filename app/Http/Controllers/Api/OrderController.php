<?php
namespace App\Http\Controllers\Api;

use App;
use App\User;
use App\Cart;
use App\Order;
use App\Product;
use App\OrderItem;
use Carbon\Carbon;
use App\UserDevice;
use App\DeliverySlot;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller 
{
    var $response = [];
    var $filter = [
        "searchText" => "",
        "itemPerPage" => 15
    ];

    public function __construct()
    {        
        
    }
    
    public function index()
    {

    }

    public function create(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'delivery_slot' => 'required|exists:delivery_slots,id',
            //'delivery_address' => 'required|exists:addresses,id',
            'payment_method' => 'required'
        ]);        

        try {
            $this->response["title"] = Lang::get('');

            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('adasdasdas');
                return $this->errorResponse($this->response);
            } 

            $items =  Cart::where('user_id' , 1)->where('status' , 1)->count(); 

            if ($items == 0) {
                $this->response["message"] = Lang::get('asdasdsadas');
                return $this->errorResponse($this->response);
            } else {
                $items =  Cart::where('user_id' , 1)->get(); 
                
                $order = new Order();
                $order->order_no = rand(10000,20000);
                $order->user_id = 1;
                $order->delivery_slot_id = $request->delivery_address;
                $order->hub_id = 1;
                $order->order_status = 'confirmed';
                $order->total_amount = 0;
                $order->payable_amount = 0;
                $order->discount_amount = 0;
                $order->paid_amount = 0;
                $order->payment_method = $request->payment_method;
                $order->is_paid = 0;
                $order->paid_status = 'unpaid';
                $order->delivery_address = 1;//$request->delivery_address;
                $order->pincode = "";
                $order->is_delivered = 0;
                $order->save();

                $amount = 0;

                foreach ($items as $value) {

                    $product = Product::find($value->product_id);

                    

                    if($value->quantity != 0 && isset($product)){                        

                        $item = new OrderItem();
                        $item->order_id = $order->id;
                        $item->product_name = "";
                        $item->product_id = $value->product_id;
                        $item->quantity = $value->quantity;
                        $item->unit = $product->unit;
                        $item->quantity = $product->quantity;
                        $item->price = $product->sale_price;
                        $item->delivery_charge = 0;
                        $item->packing_charge = 0;
                        $item->save();

                        $amount += $product->sale_price;
                    }
                }

                $order->update(['total_amount' => $amount , 'payable_amount' => $amount  ]);
 
                $this->response["message"] = Lang::get('asdasdas');
                $this->response["data"] = $order;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function cancel($id)
    {
       try{
            $order = Order::find($id);
            $order->update(["order_status" => "cancelled"]);
            $this->response["data"] = $order;
            return $this->successResponse($this->response);

       } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
}