<?php

namespace App\Http\Controllers;

use App;
use Carbon\Carbon;
use App\ShopCategory;
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

class ShopCategoryController extends Controller
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
            $this->response['data'] = ShopCategory::where('status', true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = ShopCategory::where('status', true)->count();
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
            $categories = ShopCategory::where('status', true);

            if (isset($filter['serachText'])) {
                $categories = $categories->where('first_name', 'LIKE', '%'.$filter['serachText'].'%')
                             ->orWhere('last_name', 'LIKE', '%'.$filter['serachText'].'%');
            }

            $this->response['data'] = $categories->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $categories->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:shop_categories,name',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $category = new ShopCategory();
                $category->name = $request->input('name');
                $category->slug = Str::slug($request->input('name'));
                $category->short_description = $request->has('short_description') ? $request->input('short_description') : null;
                $category->description = $request->has('description') ? $request->input('description') : null;
                $category->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $category;
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
            $category = ShopCategory::find($id);
            $category->getMedia('images');
            $category->feature  = $category->getFirstMediaUrl('features');

            $toResponse = [
                "id" => $category->id,
                "name" => $category->name,
                "slug" => $category->slug,
                "description" => $category->description,
                "short_description" => $category->short_description,
                "created_at" => $category->created_at,
                "feature" => $category->feature,
                "images" => [],
            ];

            if(isset($category->media)){

                foreach ($category->media as $key => $value) {
                    $toResponse['images'][] = [
                        "id" => $value->id,
                        "name" => $value->name,
                        "file_name" => $value->file_name,
                        "mime_type" => $value->mime_type,
                        "url" => $value->getUrl(),
                        "responsive_images" => $value->responsive_images
                    ];
                }

            }

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
            'name' => 'required|max:255|unique:categories,name,' . $id,
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $category = ShopCategory::find($id);
                $category->name = $request->input('name');
                $category->slug = Str::slug($request->input('name'));
                $category->short_description = $request->has('short_description') ? $request->input('short_description') : $category->short_description;
                $category->description = $request->has('description') ? $request->input('description') : $category->description;
                $category->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $category;
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
                $category = ShopCategory::find($request->category_id);

                $category->clearMediaCollection('features'); 

                // $feature_Count  = Media::where('collection_name' , 'features')->where('model_type' , get_class($category))->where('model_id' , $category->id)->count();
                // if(isset($feature_Count)){
                //     $category->clearMediaCollection('features'); 
                // }

                $category->addMediaFromRequest('file')
                        ->usingName(Uuid::uuid() .'.'. $request->file->getClientOriginalExtension())
                        ->toMediaCollection('features');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $category;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function uploads(Request $request)
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
                $category = ShopCategory::find($request->category_id);

                $category->addMediaFromRequest('file')
                        ->usingName(Uuid::uuid() .'.'. $request->file->getClientOriginalExtension())
                        ->toMediaCollection('images');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $category;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function destroy_media($id)
    {
        try {
            $this->response["title"] = Lang::get('');
            $media = Media::find($id);
        
            if (!isset($media)) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {

                $media->delete();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $media;
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
