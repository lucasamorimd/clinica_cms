<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestLogin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(StoreRequestLogin $request)
    {
        $data = $request->only([
            'email',
            'password'
        ]);

        $remember = $request->input('remember', false);

        if (Auth::attempt($data, $remember)) {
            return redirect()->route('home');
        } else {
            $validate = Validator::make(
                $request->all(),
                [
                    'password' => 'exists:funcionarios,password'
                ],
                [
                    'password.exists' => 'Email e/ou senha incorretos'
                ]
            );
            return redirect()->route('login')->withErrors($validate)->withInput();
        }
    }

    public function login()
    {
        return view('auth.login');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
