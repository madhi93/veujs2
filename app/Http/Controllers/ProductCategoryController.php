<?php

namespace App\Http\Controllers;

use App;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use App\ProductCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
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
            $this->response['data'] = ProductCategory::where('status', true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = ProductCategory::where('status', true)->count();
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
            $categories = ProductCategory::where('status', true);

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
            'name' => 'required|max:255|unique:product_categories,name',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $category = new ProductCategory();
                $category->name = $request->input('name');
                $category->slug = Str::slug($request->input('name'));
                $category->short_description = $request->has('short_description') ? $request->input('short_description') : null;
                $category->description = $request->has('description') ? $request->input('description') : null;
                $category->category_id = $request->has('category_id') ? $request->input('category_id') : false;
                $category->shop_id = $request->has('shop_id') ? $request->input('shop_id') : null;
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
            $category = ProductCategory::find($id);
            $category->getMedia('images');
            $category->feature  = $category->getFirstMediaUrl('features');

            $toResponse = [
                "id" => $category->id,
                "name" => $category->name,
                "slug" => $category->slug,
                "description" => $category->description,
                "parent" => $category->parent,
                "is_gift" => $category->is_gift,
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
            'name' => 'required|max:255|unique:product_categories,name,' . $id,
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $category = ProductCategory::find($id);
                $category->name = $request->input('name');
                $category->slug = Str::slug($request->input('name'));
                $category->short_description = $request->has('short_description') ? $request->input('short_description') : null;
                $category->description = $request->has('description') ? $request->input('description') : null;
                $category->category_id = $request->has('category_id') ? $request->input('category_id') : false;
                $category->shop_id = $request->has('shop_id') ? $request->input('shop_id') : null;
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
                $category = ProductCategory::find($request->category_id);

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
                $category = ProductCategory::find($request->category_id);

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
