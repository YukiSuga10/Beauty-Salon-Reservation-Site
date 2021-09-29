<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Admin;

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

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN_HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout','show_LoginForm');
        
    }
    
    public function show_LoginForm()
    {
        return view('admin.login');  
    }
    
    protected function guard()
    {
        return Auth::guard('admin');
    }
    
    
    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:8'
        ]);
        
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            $salon_id = Auth::guard('admin')->user()->id;
            $salon_name = Admin::find($salon_id)->value('name');
            return redirect('/admin/home')->with([
                "salon_id" => $salon_id,
                "name" => $salon_name]);
        }

        return back()->withInput($request->only('email', 'remember'));
    }

    
    
    
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
 
        return redirect('/login_corporation'); 
    }
}