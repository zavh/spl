<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\User;

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

    public function username()
    {
        $identity  = request()->get('identity');
        $fieldName = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        request()->merge([$fieldName => $identity]);
        return $fieldName;
    }

    protected function validateLogin(Request $request)
    {
        // dd($request);
        $id = $request->get('identity');
        $pw = $request->get('password');
        // dd($credentials);
        // $active = Auth::credentials($request);
        // dd($active);

        // dd($request);
        $this->validate( 
            $request,
            [
                'identity' => 'required|string',
                'password' => 'required|string|min:4',
                
            ],
            [
                'identity.required' => 'Username or email is required',
                'identity.same' => 'does not match email or username',
                'password.required' => 'Password is required',
                'password.min' => 'minimum 4 characters',
                'password.same' => 'passwords did not match',
            ]
        ); 
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        return array_add($credentials, 'active', '1');
    }
}
