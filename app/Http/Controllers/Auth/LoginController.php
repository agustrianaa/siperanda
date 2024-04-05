<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
    public function postlogin(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'min:8'],
        ]);


        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {
            // alert()->toast('Welcome')->position('top-end');
            return redirect()->route('dashboard');
            // if (auth()->user()->role == 'super_admin') {
            //     return redirect()->route('super_admin.dashboard');
            // } else if (auth()->user()->role == 'admin') {
            //     return redirect()->route('admin.dashboard');
            // } else if (auth()->user()->role == 'direksi') {
            //     return redirect()->route('direksi.dashboard');
            // } else if (auth()->user()->role == 'unit') {
            //     return redirect()->route('unit.dashboard');
            // }
        } else {
            return redirect()->route('login');
        }
    }
}
