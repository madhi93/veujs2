<?php

namespace App\Http\Controllers\Admin;

use App;
use Carbon\Carbon;
use Spatie\Tags\Tag;
use App\DeliveryArea;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $permissions = Permission::all();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function list($page = 1)
    {
        try {            
            $this->response['data'] = Permission::all();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Permission::count();
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

    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     if (! Gate::allows('users_manage')) {
    //         return abort(401);
    //     }
    //     Permission::create($request->all());

    //     return redirect()->route('admin.permissions.index');
    // }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:permissions,name',
            'guard_name' => 'sometimes|required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');

            // if (! Gate::allows('users_manage')) {
            //     return abort(401);
            // }
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {

                $permission = Permission::create($request->only(['name' , 'guard_name']));

                //$permissions = $request->input('permission') ? $request->input('permission') : [];
                //$permission->givePermissionTo($permissions);

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $permission;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }


    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update Permission in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Permission $permission)
    // {
    //     if (! Gate::allows('users_manage')) {
    //         return abort(401);
    //     }

    //     $permission->update($request->all());

    //     return redirect()->route('admin.permissions.index');
    // }

    public function update(Request $request, $permission_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:permissions,name,' . $permission_id,
            'guard_name' => 'sometimes|required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');

            // if (! Gate::allows('users_manage')) {
            //     return abort(401);
            // }
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {

                $permission = Permission::find($permission_id);

                $permission = $permission->update($request->only(['name' , 'guard_name']));

                //$permissions = $request->input('permission') ? $request->input('permission') : [];
                //$permission->givePermissionTo($permissions);

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $permission;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function fetch($id)
    {
        try{
            $permission = Permission::find($id);

            $this->response["data"] = $permission;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }



    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index');
    }

    public function show(Permission $permission)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        Permission::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

}