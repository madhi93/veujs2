<?php

namespace App\Http\Controllers;

use App;
use App\Hub;
use App\Stock;
use App\Product;
use Carbon\Carbon;
use App\ProductStock;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
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
            $this->response['data'] = Product::with(['stock'])->where('status', true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = Product::where('status', true)->count();
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
            $purchases = Product::where('status', true);

            if (isset($filter['serachText'])) {
                $purchases = $purchases->where('first_name', 'LIKE', '%'.$filter['serachText'].'%')
                             ->orWhere('last_name', 'LIKE', '%'.$filter['serachText'].'%');
            }

            $this->response['data'] = $purchases->with(['product' , 'hub' , 'user'])->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $purchases->count();
            return $this->successResponse($this->response);
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
            $purchase = Product::with(['product' , 'hub' , 'user'])->find($id);
            $this->response["data"] = $purchase->load(['category']);
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function histories(Request $request)
    {
        try {
            $filter = $request->all();

            $product = Product::find($request->product_id);
            $purchases = Stock::where('status', true);
            $hub = Hub::find($request->hub_id);

            if (isset($filter['product_id'])) {
                $purchases = $purchases->where('product_id', $request->product_id);
            }

            $toResponse['hub'] = $hub;
            $toResponse['product'] = $product;
            $toResponse['histories'] = $histories;

            $this->response['data'] = $toResponse;
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $purchases->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function destroy($id)
    {
    }
}
