<?php

namespace App\Http\Controllers\Auth;

use App\City;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        if (Auth::viaRemember()) {
            return redirect()->intended('/dashboard');
        }else{
            return view("auth.login");
        }
    }

    public function Register() 
    {
        return view("auth.register");
    }

    public function Authenticate(Request $request)
    {
        $remember = $request->has('remember_me');  
        
        if (Auth::attempt(['email' =>  $request->input('email'), 'password' => $request->input('password')], $remember)) {
            return redirect()->intended('/dashboard');
        }else{
            return view("auth.login" , ["error" => "You have entered an invalid username or password."]);
        }
    }

    public function RegisterUser(Request $request)
    {
        //Create a new Company Admin User
        try{
            $admin = User::where('email' , 'admin@tihalt.com')->first();

            //City::updateOrCreate(['name' => 'Bangalore' , 'country_code' => 'in'] , ['status' => true]);

            if (!isset($admin)) {  
                $admin = new User();
                $admin->email = 'admin@tihalt.com';
                $admin->first_name = 'Karthik';
                $admin->last_name = 'Raja';
                $admin->password = Hash::make('123456');
                $admin->save();
                
                return redirect('/login');
            }else{
                return  "Admin Created successfully." ;
            }
            
            
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        Auth::logout();

        return Redirect::route('/login');
    }

    public function Forgot()
    {        
        return view("auth.forgot-password");
    }
}
