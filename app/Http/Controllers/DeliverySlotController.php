<?php 

namespace App\Http\Controllers;

use App;
use Carbon\Carbon;
use App\DeliverySlot;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeliverySlotController extends Controller 
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
            $this->response['data'] = DeliverySlot::where('status' , true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = DeliverySlot::where('status' , true)->count();
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
            $slots = DeliverySlot::where('status' , true);

            if(isset($filter['serachText'])){
                $slots = $slots->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                             ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $slots->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $slots->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:delivery_slots,name',
            'description' => 'required',
            // 'start_at' => 'required',
            // 'end_at' => 'required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $slot = new DeliverySlot();
                $slot->name = $request->input('name');
               // $slot->slug = Str::slug($request->input('name'));
                $slot->description = $request->has('description') ? $request->input('description') : null;                
                $slot->start_at = $request->has('start_at') ? $request->input('start_at') : null;
                $slot->end_at = $request->has('end_at') ? $request->input('end_at') :  null;
                $slot->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $slot;
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
            $slot = DeliverySlot::find($id);
            $this->response["data"] = $slot;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:delivery_slots,name,' . $id,
            'description' => 'required',
            // 'start_at' => 'required',
            // 'end_at' => 'required',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $slot = DeliverySlot::find($id);
                $slot->name = $request->input('name');
                //$slot->slug = Str::slug($request->input('name'));
                $slot->description = $request->has('description') ? $request->input('description') : null;               
                              
                $slot->start_at = $request->has('start_at') ? $request->input('start_at') : null;
                $slot->end_at = $request->has('end_at') ? $request->input('end_at') :  null;
                $slot->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $slot;
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