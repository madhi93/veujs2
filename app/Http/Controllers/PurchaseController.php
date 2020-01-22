<?php 

namespace App\Http\Controllers;

use App;
use App\Stock;
use App\Product;
use App\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\StockPurchased;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller 
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
            $this->response['data'] = Purchase::with(['product' , 'hub' , 'user'])->where('status' , true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Purchase::where('status' , true)->count();
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
            $purchases = Purchase::where('status' , true);

            if(isset($filter['serachText'])){
                $purchases = $purchases->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                             ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $purchases->with(['product' , 'hub' , 'user'])->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $purchases->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hub_id' => 'required|exists:delivery_hubs,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $product = Product::find($request->input('product_id'));
                $purchase = new Purchase();
                $purchase->product_id = $request->input('product_id');
                $purchase->price = $request->has('price') ? $request->input('price') : 0;
                $purchase->hub_id = $request->input('hub_id');
                $purchase->unit = $product->unit;                
                $purchase->quantity = $request->input('quantity');
                $purchase->user_id = 1;
                $purchase->save();

                event(new StockPurchased($purchase , $product));

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $purchase;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
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
            $purchase = Purchase::with(['product' , 'hub' , 'user'])->find($id);
            $this->response["data"] = $purchase;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'hub_id' => 'required|exists:delivery_hubs,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $product = Product::find($request->input('product_id'));
                $purchase = Purchase::find($id);
                $purchase->product_id = $request->input('product_id');
                $purchase->price = $request->has('price') ? $request->input('price') : 0;
                $purchase->hub_id = $request->input('hub_id');
                $purchase->unit = $product->unit;                
                $purchase->quantity = $request->input('quantity');
                $purchase->user_id = 1;
                $purchase->save();

                event(new StockPurchased($purchase , $product));

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $purchase;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function destroy($id)
    {
    }

}