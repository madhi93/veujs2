<?php 

namespace App\Http\Controllers;

use App;
use App\Product;
use App\Category;
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

class ProductController extends Controller 
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
            $this->response['data'] = Product::where('status' , true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Product::where('status' , true)->count();
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
            $products = Product::where('status' , true);

            if(isset($filter['serachText'])){
                $products = $products->where('first_name' , 'LIKE' , '%'.$filter['serachText'].'%')
                             ->orWhere('last_name' , 'LIKE' , '%'.$filter['serachText'].'%');
            }    

            $this->response['data'] = $products->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $products->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:products,name',
            'unit' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'sale_price' => 'required',
            'category_id' => 'required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $product = new Product();
                $product->name = $request->input('name');
                $product->slug = Str::slug($request->input('name'));
                $product->unit = $request->input('unit');
                $product->quantity = $request->input('quantity');
                $product->price = $request->input('price');
                $product->sale_price = $request->input('sale_price');
                $product->description = $request->has('description') ? $request->input('description') : null;
                $product->in_stock = $request->has('in_stock') ? $request->input('in_stock') : false;
                $product->category_id = $request->input('category_id');
                $product->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $product;
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
            $product = Product::find($id);

            $product->getMedia('images');
            $product->feature  = $product->getFirstMediaUrl('features');
            $product->load(['category']);

            $toResponse = [
                "id" => $product->id,
                "name" => $product->name,
                "slug" => $product->slug,
                "description" => $product->description,
                "unit" => $product->unit,
                "quantity" => $product->quantity,
                "price" => $product->price,
                "sale_price" => $product->sale_price,
                "in_stock" => $product->in_stock,
                "category_id" => $product->category_id,
                "created_at" => $product->created_at,
                "feature" => $product->feature,
                "images" => [],
                "category" => $product->category
            ];

            if(isset($product->media)){

                foreach ($product->media as $key => $value) {
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
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function getCategories()
    {
        try {  
            $categories = Category::where('status' , true);

            $this->response['data'] = $categories->get(['id' , 'name']);
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:products,name,' . $id,
            'unit' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'sale_price' => 'required',
            'category_id' => 'required',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $product = Product::find($id);
                $product->name = $request->input('name');
                $product->slug = Str::slug($request->input('name'));
                $product->unit = $request->input('unit');
                $product->quantity = $request->input('quantity');
                $product->price = $request->input('price');
                $product->sale_price = $request->input('sale_price');
                $product->description = $request->has('description') ? $request->input('description') : null;
                $product->in_stock = $request->has('in_stock') ? $request->input('in_stock') : false;
                $product->category_id = $request->input('category_id');
                $product->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $product;
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
            'product_id' => 'required|exists:products,id',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $product = Product::find($request->product_id);

                $product->clearMediaCollection('features'); 

                // $feature_Count  = Media::where('collection_name' , 'features')->where('model_type' , get_class($product))->where('model_id' , $product->id)->count();
                // if(isset($feature_Count)){
                //     $product->clearMediaCollection('features'); 
                // }

                $product->addMediaFromRequest('file')
                        ->usingName(Uuid::uuid() .'.'. $request->file->getClientOriginalExtension())
                        ->toMediaCollection('features');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $product;
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
            'product_id' => 'required|exists:products,id',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $product = Product::find($request->product_id);

                $product->addMediaFromRequest('file')
                        ->usingName(Uuid::uuid() .'.'. $request->file->getClientOriginalExtension())
                        ->toMediaCollection('images');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $product;
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