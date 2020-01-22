<?php 

namespace App\Http\Controllers;

use App;
use App\Hub;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HubController extends Controller 
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
            $this->response['data'] = Hub::with(['city'])->where('status' , true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Hub::where('status' , true)->count();
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
            $hubs = Hub::where('status' , true);

            if(isset($filter['serachText'])){
                $hubs = $hubs->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                             ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $hubs->with(['city'])->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $hubs->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:delivery_hubs,name',
            'city_id' => 'required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $hub = new Hub();
                $hub->name = $request->input('name');
                //$hub->slug = Str::slug($request->input('name'));
                $hub->description = $request->has('description') ? $request->input('description') : null;
                $hub->latitude = $request->has('latitude') ? $request->input('latitude') : false;
                $hub->latitude = $request->has('longitude') ? $request->input('longitude') : false;
                $hub->address = $request->has('address') ? $request->input('address') : false;
                $hub->city_id = $request->input('city_id');
                $hub->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $hub;
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
            $hub = Hub::find($id);
            $this->response["data"] = $hub->load(['city']);
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:delivery_hubs,name,' . $id,
            'city_id' => 'required',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $hub = Hub::find($id);
                $hub->name = $request->input('name');
                //$hub->slug = Str::slug($request->input('name'));
                $hub->description = $request->has('description') ? $request->input('description') : null;
                $hub->latitude = $request->has('latitude') ? $request->input('latitude') : false;
                $hub->latitude = $request->has('latitude') ? $request->input('longitude') : false;
                $hub->address = $request->has('address') ? $request->input('address') : false;
                $hub->city_id = $request->input('city_id');
                $hub->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $hub;
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