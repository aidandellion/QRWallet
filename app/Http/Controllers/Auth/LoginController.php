<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Check if the authenticated user's account is active.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->status === 'inactive') {
            Auth::logout();

            return redirect('/login')->withErrors([
                'email' => 'Your account has been deactivated. Please contact the administrator.',
            ]);
        }
    }

    /**
     * Redirect users based on their role after login.
     */
public function redirectTo()
{
    if (Auth::user()->role_id === 1) {
        return '/admin';
    }

    return '/dashboard';
}
}