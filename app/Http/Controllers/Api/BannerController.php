<?php
namespace App\Http\Controllers\Api;

use App;
use App\Banner;
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

class BannerController extends Controller 
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

    public function list(Request $request, $page = 1)
    {     
        try { 
            $filter = $request->all(); 
            $banners = Banner::where('status' , true);           
            $this->response['data'] = $banners->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $banners->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
}