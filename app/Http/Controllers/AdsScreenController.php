<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\AdsScreen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\ShopCategory;
use Faker\Provider\Uuid;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\Models\Media;

class AdsScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function list($page = 1)
    {
        try {
            $this->response['data'] = AdsScreen::where('status', true)->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = AdsScreen::where('status', true)->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'ads_no' => 'required|unique:ads_screens',
        ]);

        try{
            if($validator->fails()){
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
//                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            }else{
                $ads_screen = new AdsScreen();
                $ads_screen->name = $request->name;
                $ads_screen->ads_no = $request->ads_no;
                $ads_screen->short_description = $request->has('short_description') ? $request->short_description : null;
                $ads_screen->description = $request->has('description') ? $request->description : null;
                $ads_screen->save();
//                $this->response["message"] = Lang::get('');
                $this->response["data"] = $ads_screen;
                return $this->successResponse($this->response);
            }
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        return $id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'ads_no' => 'required|unique:ads_screens,ads_no,'. $id,
        ]);

        try {
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $ads = AdsScreen::find($id);
                $ads->name = $request->input('name');
                $ads->ads_no = $request->input('ads_no');
                $ads->short_description = $request->has('short_description') ? $request->input('short_description') : null;
                $ads->description = $request->has('description') ? $request->input('description') : null;
                $ads->save();
                $this->response["data"] = $ads;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }


    public function fetch($id)
    {
        try {
            $ad = AdsScreen::find($id);
            $toResponse = [
                "id" => $ad->id,
                "name" => $ad->name,
                "ads_no" => $ad->ads_no,
                "description" => $ad->description,
                "short_description" => $ad->short_description,
                "category_id" => $ad->category_id,
                "created_at" => $ad->created_at,
            ];

            $this->response["data"] = $toResponse;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $ads = AdsScreen::find($id);
            $ads->delete();
            $toResponse = [
                "success" => $id.'is deleted successfully',
            ];
            $this->response["data"] = $toResponse;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

}
