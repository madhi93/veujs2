<?php 

namespace App\Http\Controllers;

use App;
use Carbon\Carbon;
use Spatie\Tags\Tag;
use App\DeliveryArea;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeliveryAreaController extends Controller 
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

  public function list(Request $request, $page = 1)
    {     
        try {  
            
            $hub_id =  $request->has('hub_id') ? $request->input('hub_id') : 0;

            $this->response['data'] = DeliveryArea::where('hub_id' , $hub_id)->where('status' , true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = DeliveryArea::where('hub_id' , $hub_id)->where('status' , true)->count();
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
            $hub_id =  $request->has('hub_id') ? $request->input('hub_id') : 0; 
            $areas = DeliveryArea::where('hub_id' , $hub_id)->where('status' , true);

            if(isset($filter['serachText'])){
                $areas = $areas->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                             ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $areas->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $areas->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'pin_code' => 'required|max:255|unique:delivery_areas,pin_code',
            //'geo_fence' => 'required',
            'hub_id' => 'required',
            'area_tags' => 'required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $area = new DeliveryArea();
                $area->name = $request->input('name');
                $area->pin_code = $request->input('pin_code');
                $area->geo_fence = $request->has('geo_fence') ? $request->input('geo_fence') : json_encode([]);
                $area->hub_id = $request->input('hub_id');
                $area->save();

                $area->syncTagsWithType($request->area_tags);

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $area;
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
            $area = DeliveryArea::find($id);

            $tags = $area->tags()->pluck('name');

            $area->area_tags = $tags;

            $this->response["data"] = $area;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'pin_code' => 'required|max:255|unique:delivery_areas,pin_code,' . $id,
            //'geo_fence' => 'required',
            'hub_id' => 'required',
            'area_tags' => 'required',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $area = DeliveryArea::find($id);
                $area->name = $request->input('name');
                $area->pin_code = $request->input('pin_code');
                $area->geo_fence = $request->has('geo_fence') ? $request->input('geo_fence') : json_encode([]);
                $area->hub_id = $request->input('hub_id');
                $area->save();

                $area->syncTagsWithType($request->area_tags , 'in_bangalore');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $area;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function tags(Request $request , $city_id = null)
    {
        try {      
            $filter = $request->all();
            //$hub_id =  $request->has('city_id') ? $request->input('hub_id') : 0; 
            $tags = Tag::where('type' , 'in_bangalore')->get();

            $this->response['data'] = $tags->map(function($tag) { return ["text" => $tag->name]; });
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