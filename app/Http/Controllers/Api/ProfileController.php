<?php
namespace App\Http\Controllers\Api;

use App;
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

class ProfileController extends Controller 
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

    public function profile() 
    { 
        try {
            $user = Auth::user();

            if(isset($user)){
                $user->user_type = 'user';
            }
            
            $this->response["title"] = Lang::get('');        
            $this->response["message"] = Lang::get('');
            $this->response["data"] = $user;
            return $this->successResponse($this->response);

        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }  
    } 

    public function update(Request $request) 
    { 
        try {
            $user = App\User::find(Auth::user()->id);

            if(!isset($user)){
                $this->response["message"] = "User not found";
                return $this->errorResponse($this->response);
            } 

            $user->first_name = $request->has('first_name') ? $request->input('first_name') : $user->first_name;
            $user->last_name = $request->has('last_name') ? $request->input('last_name') : $user->last_name;
            $user->email = $request->has('email') ? $request->input('email') : $user->email;
            $user->save();
            
            $this->response["title"] = "";        
            $this->response["message"] = "";
            $this->response["data"] = $user;
            return $this->successResponse($this->response);

        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }  
    } 

    public function image_upload(Request $request)
    {
        try {
            $user = Auth::user();

            if(!isset($user)){
                $this->response["message"] = "User not found";
                return $this->errorResponse($this->response);
            } 

            $file = null;//$user->uploadProfileImage($request , 'profile_image' , 'profile');
            
            $this->response["title"] = "";        
            $this->response["message"] = "";
            $this->response["data"] = $file;
            return $this->successResponse($this->response);

        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }  
    }

    public function profile_image()
    {
        try {
            $user = Auth::user();

            $file = '';//ProfileImage::where('status' , 1)->where('model_type' , get_class($user))->where('model_id' , $user->id)->first();

            $url = asset("storage/" . $file->path);
            
            $this->response["title"] = "";        
            $this->response["message"] = "";
            return $this->successResponse($this->response);

        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }  
    }

}
