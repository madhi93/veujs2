<?php
namespace App\Http\Controllers\Api;

use App;
use App\Address;
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

class AddressController extends Controller 
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
            $user = Auth::user();
            $addresses = Address::where("user_type" , get_class($user))
                                ->where('user_id' , $user->id)
                                ->get();

            $this->response['data'] = $addresses;
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $addresses->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'building_no' => 'required|max:255|numeric',
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

                $address = new Address();
                $address->building_no = $request->has('building_no') ? $request->input('building_no') : null;
                $address->address1 = $request->has('address1') ? $request->input('address1') : null;
                $address->address2 = $request->has('address2') ? $request->input('address2') : null;
                $address->land_mark = $request->has('land_mark') ? $request->input('land_mark') : null;
                $address->address_type = $request->has('address_type') ? $request->input('address_type') : null;
                $address->city_id = $request->has('city_id') ? $request->input('city_id') : null;
                $address->city_name = $request->has('city_name') ? $request->input('city_name') : null;
                $address->pincode = $request->has('pincode') ? $request->input('pincode') : null;
                $address->country_code = $request->has('country_code') ? $request->input('country_code') : null;
                $address->user_type = get_class($user);
                $address->user_id = $user->id;
                $address->status = 1;
                $address->save();

                $meta = [];

                $meta['contact_name'] = $request->has('contact_name') ? $request->input('contact_name') : $user->fullname;
                $meta['contact_number'] = $request->has('contact_number') ? $request->input('contact_number') : $user->std_code."" .$user->mobile;
                $meta['state'] = $request->has('state') ? $request->input('state') : null;


                if(isset($meta)){
                    $address->update(["meta" => $meta]);
                }

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $address;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }



    public function fetch($id) 
    { 
        try {
            $user = Auth::user();

            $address = Address::where("user_type" , get_class($user))
                                ->where('user_id' , $user->id)
                                ->where('id' , $id)
                                ->first();
            
            $this->response["title"] = Lang::get('');        
            $this->response["message"] = Lang::get('');
            $this->response["data"] = $address;
            return $this->successResponse($this->response);

        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }  
    } 


    public function update(Request $request) 
    { 
        $validator = Validator::make($request->all(), [
            'building_no' => 'required|max:255',
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

                $address = Address::where("user_type" , get_class($user))
                                ->where('user_id' , $user->id)
                                ->where('id' , $id)
                                ->first();

                $this->response["title"] = Lang::get('Delete Address');
        
                if (!isset($address)) {
                    $this->response["message"] = Lang::get('Address not found.');
                    return $this->errorResponse($this->response);
                } 

                $address->building_no = $request->has('building_no') ? $request->input('building_no') : null;
                $address->address1 = $request->has('address1') ? $request->input('address1') : null;
                $address->address2 = $request->has('address2') ? $request->input('address2') : null;
                $address->land_mark = $request->has('land_mark') ? $request->input('land_mark') : null;
                $address->address_type = $request->has('address_type') ? $request->input('address_type') : null;
                $address->city_id = $request->has('city_id') ? $request->input('city_id') : null;
                $address->city_name = $request->has('city_name') ? $request->input('city_name') : null;
                $address->pincode = $request->has('pincode') ? $request->input('pincode') : null;
                $address->country_code = $request->has('country_code') ? $request->input('country_code') : null;
                $address->is_default = $request->has('is_default') ? $request->input('is_default') : false;
                $address->user_type = get_class($user);
                $address->user_id = $user->id;
                $address->status = 1;
                $address->save();

                $meta = [];

                $meta['contact_name'] = $request->has('contact_name') ? $request->input('contact_name') : $user->fullname;
                $meta['contact_number'] = $request->has('contact_number') ? $request->input('contact_number') : $user->std_code."" .$user->mobile;
                $meta['state'] = $request->has('state') ? $request->input('state') : null;


                if(isset($meta)){
                    $address->update(["meta" => $meta]);
                }

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $address;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    } 

    public function make_default($id)
    {
        try {

            $user = Auth::user();

            $address = Address::where("user_type" , get_class($user))
                                ->where('user_id' , $user->id)
                                ->where('id' , $id)
                                ->first();

            $this->response["title"] = Lang::get('Make default Address');
        
            if (!isset($address)) {
                $this->response["message"] = Lang::get('Address not found.');
                return $this->errorResponse($this->response);
            } else {

                Address::where("user_type" , get_class($user))
                                ->where('user_id' , $user->id)
                                ->where('id' , '<>' , $id)
                                ->update(['is_default' => 1]);

                $address->update(['is_default' => 1]);
                $this->response["message"] = Lang::get('Address Make defalt sucessfully.');
                $this->response["data"] = $address;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function destory($id)
    {
        try {

            $user = Auth::user();

            $address = Address::where("user_type" , get_class($user))
                                ->where('user_id' , $user->id)
                                ->where('id' , $id)
                                ->where('status' , true)
                                ->first();

            $this->response["title"] = Lang::get('Delete Address');
        
            if (!isset($address)) {
                $this->response["message"] = Lang::get('Address not found.');
                return $this->errorResponse($this->response);
            } else {

                $address->update(['status' => 0]);
                $this->response["message"] = Lang::get('dDelete address successfull.');
                $this->response["data"] = $address;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
}
