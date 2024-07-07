<?php

namespace App\Http\Controllers\Auth;

use RealRashid\SweetAlert\Facades\Alert;

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

    public function login(Request $request)
{
    $input = $request->all();
    $this->validate($request, [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'min:8'],
    ]);

    if (auth()->attempt(['email' => $input['email'], 'password' => $input['password']])) {
        Alert::toast('Hello ' . auth()->user()->email . ', selamat datang kembali!', 'success')->position('top-end');
        return redirect()->route('dashboard')->with('success', 'Hello ' . auth()->user()->email . ', selamat datang kembali!');
    } else {
        Alert::error('Error', 'Email atau Password Salah');
        return redirect()->route('login')->with('error', 'Email atau Password Salah');
    }
}
}
