<?php
namespace App\Http\Controllers\Api;

use App;
use App\Cart;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class CartController extends Controller 
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

    public function list($page = 1)
    {     
        try {            
            $this->response['data'] = Cart::where('status' , true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Cart::where('status' , true)->count();
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
            $carts = Cart::where('status' , true);

            if(isset($filter['serachText'])){
                $carts = $carts->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                               ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $carts->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $carts->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric',
            'product_id' => 'required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $user = Auth::user();

                $cart = Cart::where('product_id' , $request->input('product_id'))->where('user_id' , $user->id)->first();

                if($request->input('product_id') != 0){

                    if(!isset($cart)){
                        $cart = new Cart();
                        $cart->user_id = $user->id;
                    }
                    
                    $cart->quantity = $request->input('quantity');
                    $cart->product_id = $request->input('product_id');
                    $cart->save();
                } elseif(isset($cart)){
                    $cart->delete();
                }

                

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $cart;
                 $this->successResponse($this->response);
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
            $cart = Cart::where('id' , $id)->where('user_id' , Auth::id())->first();
            $this->response["data"] = $cart;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric',
            'product_id' => 'required',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $cart = Cart::find($id);

                if($request->input('quantity') != 0){
                    $cart->quantity = $request->input('quantity');
                    //$cart->product_id = $request->input('product_id');
                    //$cart->user_id = 1;//$request->input('city_id');
                    $cart->save();
                } else{
                    $cart->delete();
                }
                
                $this->response["message"] = Lang::get('');
                $this->response["data"] = $cart;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function destroy($id)
    {
        try{
            $cart = Cart::where('id' , $id)->where('user_id' , Auth::id())->first();

            if(!isset($cart)){
                $this->response["title"] = Lang::get('Delete Cart');
                $this->response["message"] = Lang::get('Delete Cart Faild.');
                return $this->successResponse($this->response);            
            }

            $carts = Cart::where('user_id' , Auth::id())->get();

            $this->response["data"] = $carts;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
}