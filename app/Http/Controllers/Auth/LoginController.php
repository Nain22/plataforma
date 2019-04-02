<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\UserSocialAccount;
use App\Student;
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

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('memory_limit', '-1');
        $this->middleware('guest')->except('logout');
    }

    public function logout (Request $request){
        //cerramos session de la aplicacion
        auth()->logout();
        //limpiamos todas las sessiones
        session()->flush();
        return redirect('/login');
    }

    public function redirectToProvider (string $driver) {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback (string $driver){
        //cuando se dara el boton en canclar dentrara a este if
        if( ! request()->has('code') || request()->has('denied')){
            //mostramos un mensaje
            session()->flash('message' , ['danger' , __("Inicio de sessión cancelado")]);
            return redirect('login');
        }
        $socialUser = Socialite::driver($driver)->user();

        $user = null;
        $success = true;
        $email = $socialUser->email;
        $check = User::whereEmail($email)->first(); // SELECT TOP1 * FROM `users` WHERE email = $email
        if($check) {
            $user=$check;
        }else{
            \DB::beginTransaction();
            try{
                $user = User::create([
                    "name" => $socialUser->name,
                    "email" => $email
                ]);
                UserSocialAccount::create([
                    "user_id" => $user->id,
                    "provider" => $driver,
                    "provider_uid" => $socialUser->id
                ]);
                Student::create([
                    "user_id" => $user->id
                ]);
            }catch(\Exception $exception){
                $success = $exception->getMessage();
                \DB::rollBack();
            }
        }
        if($success === true){
            \DB::commit();
            auth()->loginUsingId($user->id);
            return redirect(route('home'));
        }
        session()->flash('message' , ['danger' , $success]);
        return redirect('login');

    }
}
