<?php 

namespace App\Http\Controllers;

use App;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller 
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
            $this->response['data'] = Admin::where('status' , true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Admin::where('status' , true)->count();
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
            $user = Admin::where('status' , true);

            if(isset($filter['serachText'])){
                $user = $user->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                             ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $user->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $user->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'user_roles' => 'required',
            //'password' => 'required|min:6',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $user = new Admin();
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');
                $user->password = $request->has('password') ? Hash::make($request->input('password')) : Hash::make('12345678');;//Hash::make(str_random(8));
                $user->save();

                $user->syncRoles($request->user_roles);

                //$file = $user->uploadFile($request , 'profile_image' , 'user/profile');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $user;
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
            $user = Admin::find($id);
            $user->load('roles');

            $roles = $user->roles;

            $roles = $roles->map(function($role) {
                return $role->name;
            });

            $user['user_roles'] = $roles;

            $this->response["data"] = $user;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' .$id,
            'user_roles' => 'required'
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $user = Admin::find($id);
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');

                if($request->has('password')){
                    $user->password = Hash::make($request->input('password'));                    
                }
                
                $user->save();

                $user->syncRoles($request->user_roles);

                //$user->file = $user->uploadFile($request , 'profileImage' , 'user/profile');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $user;
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