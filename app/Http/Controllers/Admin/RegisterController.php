<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //use RegistersAdmins;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    
    public function show_RegisterForm(){
        return view('admin.register');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered_admin($admin = $this->create($request->all())));

        $this->guard()->login($admin);

        return $this->redirect($this->redirectPath());
    }
     
     
     
    public function register_admin(Request $request){
        $admins = $request->all();
        
        $inf_admins = Admin::insert([
            "email" => $admins['email'],
            "password" => Hash::make($admins['password']),
            ]);
            
        return $this->registered($request, $admin)
                        ?: redirect($this->redirectPath());
    }
    protected function create(Request $data)
    {
        return Admin::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
