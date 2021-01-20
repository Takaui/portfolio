<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/admin/clients/list';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    
    
    //以下function追加
    public function showLoginForm()
   {
       return view('admin.auth.login');
   }
   
   protected function guard()
   {
       return \Auth::guard('admin');
   }

   public function logout(Request $request)
   {
       $this->guard('admin')->logout();
       // $request->session()->invalidate(); これが全部のSessionを消してしまう
       return redirect('/admin');
    }
    
}
