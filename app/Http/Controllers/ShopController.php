<?php

namespace App\Http\Controllers;

use App;
use App\Shop;
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

class ShopController extends Controller
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
            $this->response['data'] = Shop::with('category')->where('status', true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Shop::where('status', true)->count();
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
            $categories = Shop::where('status', true);

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
            'name' => 'required|max:255',
            'shop_no' => 'required|unique:shops,shop_no',
            'category_id' => 'required|exists:shop_categories,id',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $shop = new Shop();
                $shop->name = $request->input('name');
                $shop->shop_no = $request->input('shop_no');
                $shop->short_description = $request->has('short_description') ? $request->input('short_description') : null;
                $shop->description = $request->has('description') ? $request->input('description') : null;
                $shop->category_id = $request->has('category_id') ? $request->input('category_id') : null;
                $shop->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $shop;
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
            $shop = Shop::find($id);
            $shop->getMedia('images');
            $shop->feature = $shop->getFirstMediaUrl('features');

            $shop->load('category');

            $toResponse = [
                "id" => $shop->id,
                "name" => $shop->name,
                "shop_no" => $shop->shop_no,
                "description" => $shop->description,
                "short_description" => $shop->short_description,
                "category_id" => $shop->category_id,
                "created_at" => $shop->created_at,
                "feature" => $shop->feature,
                "images" => [],
                'category' => $shop->category
            ];

            if (isset($shop->media)) {
                foreach ($shop->media as $key => $value) {
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
            'name' => 'required|max:255',
            'shop_no' => 'required|unique:shops,shop_no,' . $id,
            'category_id' => 'required|exists:shop_categories,id',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $shop = Shop::find($id);
                $shop->name = $request->input('name');
                $shop->shop_no = $request->input('shop_no');
                $shop->short_description = $request->has('short_description') ? $request->input('short_description') : null;
                $shop->description = $request->has('description') ? $request->input('description') : null;
                $shop->category_id = $request->has('category_id') ? $request->input('category_id') : null;
                $shop->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $shop;
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
                $shop = Shop::find($request->shop_id);

                $shop->clearMediaCollection('features');

                // $feature_Count  = Media::where('collection_name' , 'features')->where('model_type' , get_class($shop))->where('model_id' , $shop->id)->count();
                // if(isset($feature_Count)){
                //     $shop->clearMediaCollection('features');
                // }

                $shop->addMediaFromRequest('file')
                        ->usingName(Uuid::uuid() .'.'. $request->file->getClientOriginalExtension())
                        ->toMediaCollection('features');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $shop;
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
                $shop = Shop::find($request->shop_id);

                $shop->addMediaFromRequest('file')
                        ->usingName(Uuid::uuid() .'.'. $request->file->getClientOriginalExtension())
                        ->toMediaCollection('images');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $shop;
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

    public function fetch_categories(Request $request , $page = 1)
    {
        try {
            $filter = $request->all();
            $categories = ShopCategory::where('status', true);

            if (isset($filter['serachText'])) {
                $categories = $categories->where('first_name', 'LIKE', '%'.$filter['serachText'].'%')
                             ->orWhere('last_name', 'LIKE', '%'.$filter['serachText'].'%');
            }

            $this->response['data'] = $categories->get(['id' , 'name']);
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $categories->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
}