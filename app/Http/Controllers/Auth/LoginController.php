<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function redirectToGoogle()
    {
        // dd(Socialite::driver('google'));
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();


    //    dd($email);

        $this->_registerOrLoginUser($user);

        //Return home after login
        return redirect()->route('home');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        dd('dsd');
        $user = Socialite::driver('facebook')->stateless()->user();


        $this->_registerOrLoginUser($user);

        //Return home after login
        return redirect()->route('home');
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
        dd('dsdf');
    }

    public function handleGithubCallback()
    {

        $user = Socialite::driver('github')->stateless()->user();

        $this->_registerOrLoginUser($user);

        //Return home after login
        return redirect()->route('home');
    }

    protected function _registerOrLoginUser($data)
    {
        // dd($data->name);
        $user = User::where('email', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
        }

        Auth::login($user);
    }
}
