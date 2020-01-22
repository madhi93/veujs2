<?php
namespace App\Http\Controllers\Api;

use App;
use App\User;
use Carbon\Carbon;
use App\UserDevice;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller 
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
    
    public function send_mobie_otp(Request $request)
    {
        $validator = Validator::make($request, [
            'std_code' => 'required',
            'mobile' => 'required|numeric',
        ]);

        try{
            $user = User::where('mobile' , $request->mobile)->first();

            UserDevice::updateOrCreate([
                "mobile" => $request->mobile,
                'country_code' => $request->std_code,
                "device_id" => $request->header('x-device-id'),
                "device_token" => $request->header('x-device-token'),
            ],[                
                "otp" => rand(1000,9999),
                "device_type" => $request->header('x-device-type') ,
                "os_version" => $request->header('x-device-os') ,
                "app_version" => $request->header('x-application-version') ,
                "current" => true,
                "sns_arn" => "fghg",
                'user_id' => isset($user) ? $user->id : null
            ]);

            return $this->successResponse($this->response);

        } catch (\Exception $e){
            $this->response['message'] = "Something went worng!.";
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function verify_otp(Request $request)
    {
        //return $request->all();
        $validator = Validator::make($request, [
            'otp' => 'required|numeric|exists:user_devices,otp',
            'std_code' => 'required|exists:user_devices,country_code',
            'mobile' => 'required|numeric|exists:user_devices,mobile',
        ]);

        try{    
            $where = [
                'otp' => $request->otp,
                'mobile' => $request->mobile,
                'country_code' => $request->std_code,
                'device_token' => $request->header('x-device-token')
            ];

            $device =  UserDevice::where($where)->first();
            
            if(isset($device))
            {
                $where1 = [
                    'mobile'=> $request->mobile,
                    'country_code' => $request->std_code,
                    'device_token' => $request->header('x-device-token'),
                    'status' => 1
                ];


                UserDevice::where($where1)->update(['current' => 0]);

                $device->status = 1;
                $device->current = 1;
                $device->save();

                if(isset($device->user_id)){
                    $user = User::whereMobile($request->username)->first();
                    //$success['token'] =  $user->createToken('karthik')->accessToken; 
                    $this->response['data'] = ['user_found' => false , 'login' => false , 'token' => $user->createToken('karthik')->accessToken];
                } else{
                    $this->response['data'] = ['user_found' => false , 'login' => false , 'token' => null];
                }    
                
                return $this->successResponse($this->response);
            }else{
                return response()->json(['status' => 'false' , 'message' => 'Otp is Invalid' ]);
            }
        } catch (\Exception $e){
            $this->response['message'] = "Something went worng!.";
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }  

    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email',
            'mobile'  => 'required|max:10|min:10|unique:users,mobile',
            'password' => 'sometimes|required|min:6',
            'std_code' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response["errors"] = $validator->errors();
            $this->response["old_data"] = $request->all();
            $this->response["message"] = Lang::get('');
            return $this->errorResponse($this->response);
        }
       
        try {

            $where = [
                'otp' => $request->otp,
                'mobile' => $request->mobile,
                'country_code' => $request->std_code,
                'device_token' => $request->header('x-device-token')
            ];

            $device =  UserDevice::where($where)->first();

            if(!isset($device)){
                return $this->errorResponse($this->response);
            }

            $user = new App\User();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->has('email') ? $request->input('email') : null;
            $user->mobile = $request->input('mobile');
            $user->std_code = $request->input('std_code');
            //$user->active_status = 'active';
            $user->is_customer = true;
            $user->password = $request->has('password') ? Hash::make($request->input('password')) : Hash::make(Str::random(6));
            $user->save();

            UserDevice::where([
                "user_id" => $user->id , 
                "device_id" => $request->header('x-device-id'),
                "device_token" => $request->header('x-device-token'),
            ])->update(["current" => true]);

            //$file = $user->uploadFile($request , 'profile_image' , 'user/profile');

            $this->response["message"] = Lang::get('');
            $this->response["data"] = $user;
            return $this->successResponse($this->response);

        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }     
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'current_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        try {
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = "Password update failed.!";
                return $this->errorResponse($this->response);
            } 
            $user = App\User::find(Auth::id());

            if (!Hash::check($request->current_password, $user->password)) {
                $this->response['message'] = 'Current password does not match';
                return $this->errorResponse($this->response);
            }

            $user->password = Hash::make($request->password);
            $user->save();
            $this->response["message"] = "Password Update Successfully.!";
            $this->response['data'] = [];
            return $this->successResponse($this->response);

        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request, [
            'email' => 'required|email|unique:users',
        ]);

        try{            
            return response()->json(['message' => trans('api.email_available')]);
        } catch (\Exception $e) {
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function send_otp(Request $request)
    {
        $validator = Validator::make($request, [
            'username' => 'required|numeric',                
        ]);

        $mobileno = $request->username;
        $otp = $this->otp_generate();
        //sendsms($mobileno, $otp);
        return response()->json(['otp' => $otp]);
    }

    public function voice_sms(Request $request)
    {
        $validator = Validator::make($request, [            
            'username' => 'required|numeric',            
        ]);

        $mobile = $request->username;
        $mobileno = "91".$mobile."";

        $otp = $this->otp_generate();

        //voicesms($mobileno,$otp);

        return response()->json(['otp' => $otp]);

    }

    public function otp_generate()
    {
        $otp = mt_rand(1000, 9999);
    
        $count = UserDevice::where('otp',$otp)->count();
        if($count!=0)
        {
           $otp = $this->otp_generate();
        }

        return $otp;
    }
   
    public function logout(Request $request)
    {
        $accessToken = Auth::user();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        //$accessToken->revoke();
        $this->response['message'] = "User logout successfull.";
        return $this->successResponse($this->response);
        // try {
        //     User::where('id', $request->id)->update(['device_id'=> '', 'device_token' => '']);
        //     return response()->json(['message' => trans('api.logout_success')]);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => trans('api.something_went_wrong')], 500);
        // }
    }



    public function login(Request $request)
    { 
        try{
            $user = null;

            if(Auth::attempt(['email' => request('username'), 'password' => request('password')])){ 
                $user = Auth::user();
            }else if(Auth::attempt(['mobile' => request('username'), 'password' => request('password')])){ 
                $user = Auth::user(); 
            } 

            if(isset($user)){  
                
                UserDevice::where([
                    "user_id" => $user->id , 
                    "device_id" => $request->header('x-device-id'),
                    "device_token" => $request->header('x-device-token'),
                ])->update(["current" => true]);

                //$success['token'] =  $user->createToken('uladriver')->accessToken; 
                //$this->response['data']['token'] = $success['token'];

                $this->response['data'] = ['user_found' => true , 'login' => true , 'token' => $user->createToken('karthik')->accessToken];

                return $this->successResponse($this->response);
            }else{ 
                $this->response['data'] = ['user_found' => false , 'login' => false , 'token' => null];
                $this->response['message'] = "Unauthorised";
                return $this->errorResponse($this->response);
            } 

        } catch (\Exception $e){
            $this->response['message'] = "Something went worng!.";
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
        
    }

    public function signup(Request $request) 
    { 
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email',
            'mobile'  => 'required|max:10|min:10|unique:users,mobile',
            'password' => 'sometimes|required|min:6',
            'std_code' => 'required'
        ]);

        if ($validator->fails()) {
            $this->response["errors"] = $validator->errors();
            $this->response["old_data"] = $request->all();
            $this->response["message"] = Lang::get('');
            return $this->errorResponse($this->response);
        }
       
        try {

            $where = [
                'otp' => $request->otp,
                'mobile' => $request->mobile,
                'country_code' => $request->std_code,
                'device_token' => $request->header('x-device-token')
            ];

            $device =  UserDevice::where($where)->first();

            if(!isset($device)){
                UserDevice::updateOrCreate([
                    "mobile" => $request->mobile,
                    'country_code' => $request->std_code,
                    "device_id" => $request->header('x-device-id'),
                    "device_token" => $request->header('x-device-token'),
                ],[                
                    "otp" => rand(1000,9999),
                    "device_type" => $request->header('x-device-type') ,
                    "os_version" => $request->header('x-device-os') ,
                    "app_version" => $request->header('x-application-version') ,
                    "current" => true,
                    "sns_arn" => "fghg",
                    'user_id' => isset($user) ? $user->id : null
                ]);
            }

            $user = new App\User();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->has('email') ? $request->input('email') : null;
            $user->mobile = $request->input('mobile');
            $user->std_code = $request->input('std_code');
            //$user->active_status = 'active';
            $user->password = $request->has('password') ? Hash::make($request->input('password')) : Hash::make(Str::random(6));
            $user->save();

            UserDevice::where([
                "user_id" => $user->id , 
                "device_id" => $request->header('x-device-id'),
                "device_token" => $request->header('x-device-token'),
            ])->update(["current" => true]);

            //$file = $user->uploadFile($request , 'profile_image' , 'user/profile');

            $this->response["message"] = Lang::get('');
            $this->response["data"] = $user;
            return $this->successResponse($this->response);

        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }     
    }
}