<?php 

namespace App\Http\Controllers;

use App;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller 
{
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

  public function list($page = 1)
    {     
        try {            
            $this->response['data'] = Order::withCount(['items as item_count'])->with(['items' , 'items.product' ,  'hub' , 'customer'])->where('status' , true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Order::where('status' , true)->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function search(Request $request , $page = 1)
    {
        try {      
            $filter = $request->all(); 
            $purchases = Order::where('status' , true);

            if(isset($filter['serachText'])){
                $purchases = $purchases->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                             ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $purchases->with(['product' , 'hub' , 'customer'])->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $purchases->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function show($id)
    {

    }

    public function fetch($id)
    {
        try{
            $order = Order::with(['hub' , 'customer' , 'items'])->find($id);

            //$order->update_timeline('order_placed' , "assdsc cddsd");

            $this->response["data"] = $order;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update_status(Request $request , $order_id)
    {
       try{

           $order = Order::find($order_id);

           if($request->has('status') && isset($order)){
                $order->update_timeline($request->status);              
           }

           $this->response["data"] = $order;
           return $this->successResponse($this->response);

       } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
       }
    }

    public function destroy($id)
    {
    }

}