<?php

namespace App\Http\Controllers;

use App;
use App\Banner;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public $filter = [
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
            $this->response['data'] = Banner::where('status', true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Banner::where('status', true)->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function search(Request $request, $page = 1)
    {
        try {
            $filter = $request->all();
            $banners = Banner::where('status', true);

            if (isset($filter['serachText'])) {
                $banners = $banners->where('first_name', 'LIKE', '%'.$filter['serachText'].'%')
                             ->orWhere('last_name', 'LIKE', '%'.$filter['serachText'].'%');
            }

            $this->response['data'] = $banners->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $banners->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:banners,name',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $banner = new Banner();
                $banner->name = $request->input('name');
                $banner->alt_text = $request->input('alt_text');
                //$banner->image_path  = $request->has('image_path') ? $request->input('image_path') : null;
                $banner->url = $request->has('url') ? $request->input('url') : null;
                $banner->target = $request->has('target') ? $request->input('target') : null;
                $banner->active_status = $request->has('active_status') ? $request->input('active_status') : 'ENABLED';
                $banner->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $banner;
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
        try {
            $banner = Banner::find($id);
            $banner->getMedia('images');
            $banner->feature  = $banner->getFirstMediaUrl('features');

            $toResponse = [
                "id" => $banner->id,
                "name" => $banner->name,
                "image_path" => $banner->feature,
                "name" => $banner->name,
                "url" => $banner->url,
                "target" => $banner->target,
                "alt_text" => $banner->alt_text,
                "active_status" => $banner->active_status,
                "created_at" => $banner->created_at,
            ];

            $this->response["data"] = $toResponse;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:banners,name,' . $id,
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $banner = Banner::find($id);
                $banner->name = $request->input('name');
                $banner->alt_text = $request->input('alt_text');
                //$banner->image_path  = $request->has('image_path') ? $request->input('image_path') : null;
                $banner->url = $request->has('url') ? $request->input('url') : null;
                $banner->target = $request->has('target') ? $request->input('target') : null;
                $banner->active_status = $request->has('active_status') ? $request->input('active_status') : 'ENABLED';
                $banner->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $banner;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function upload_feature_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $banner = Banner::find($request->banner_id);

                $banner->clearMediaCollection('features'); 

                // $feature_Count  = Media::where('collection_name' , 'features')->where('model_type' , get_class($banner))->where('model_id' , $banner->id)->count();
                // if(isset($feature_Count)){
                //     $banner->clearMediaCollection('features'); 
                // }

                $banner->addMediaFromRequest('file')
                        ->usingName(Uuid::uuid() .'.'. $request->file->getClientOriginalExtension())
                        ->toMediaCollection('features');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $banner;
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
