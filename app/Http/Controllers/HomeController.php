<?php 

namespace App\Http\Controllers;

use App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller 
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
    return view('layouts.app');   
  } 

  public function fetchCities()
  {
    try {            
        $this->response['data'] = App\City::get();
        return $this->successResponse($this->response);
    } catch (\Exception $e){
        $this->response['error_message'] = $e->getMessage();
        return $this->errorResponse($this->response);
    } 
  } 

  public function fetchProducts()
  {
    try {            
        $this->response['data'] = App\Product::with('category:id,name')->get(['id' , 'name' , 'category_id']);
        return $this->successResponse($this->response);
    } catch (\Exception $e){
        $this->response['error_message'] = $e->getMessage();
        return $this->errorResponse($this->response);
    } 
  } 

  public function fetchHubs()
  {
    try {            
        $this->response['data'] = App\Hub::get(['id' , 'name' ]);
        return $this->successResponse($this->response);
    } catch (\Exception $e){
        $this->response['error_message'] = $e->getMessage();
        return $this->errorResponse($this->response);
    } 
  } 

  public function roles()
  {
    try {            
        $this->response['data'] = Role::get(['name as text' ]);
        return $this->successResponse($this->response);
    } catch (\Exception $e){
        $this->response['error_message'] = $e->getMessage();
        return $this->errorResponse($this->response);
    } 
  } 

}