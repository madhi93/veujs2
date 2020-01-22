<?php
namespace App\Http\Controllers\Api;

use App;
use App\Cart;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\DeliverySlot;
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

class HomeController extends Controller 
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

    public function getDeliverySlots()
    {
        
    }
}