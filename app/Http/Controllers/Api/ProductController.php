<?php
namespace App\Http\Controllers\Api;

use App;
use App\Product;
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

class ProductController extends Controller 
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

    public function list(Request $request , $page = 1)
    {     
        try { 
            $filter = $request->all(); 
            $products = Product::where('status' , true);
            
            if(isset($filter['category'])){
                $products->where('category_id' , $filter['category']);
            }

            $this->response['data'] = $products->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $products->count();
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
            $products = Product::where('status' , true);

            if(isset($filter['serachText'])){
                $products = $products->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                               ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $products->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $products->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function fetch($id)
    {
        try{
            $product = Product::where('id' , $id)->where('user_id' , Auth::id())->first();
            $this->response["data"] = $product;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
}