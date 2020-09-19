<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\WEB\Controllers\Auth;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class RegisterController extends AuthController
{

    use RegistersUsers;
    public $loginTo = "login";
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        $this->username_method($request);
        $UserModel = config('auth.providers.users.model');
        if(!config('auth.enter.register', true) && !$UserModel::count())
        {
            throw ValidationException::withMessages([
                $this->username_method($request) => [_t('auth.register.disabled')],
            ]);
        }
        $this->validator($request)->validate();

        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request, $user)
            ?: redirect($this->loginTo);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(Request $request, $update = false)
    {
        $validation = [
            'email'  => 'required|string|email|max:255|unique:users',
            'mobile' => 'required',
        ];
        $username = $this->username_method($request) == 'username' ? 'email' : $this->username_method($request);

        return Validator::make($request->all(), [
            'password' => 'required|string|min:6',
            $username  => $validation[$username]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $username = $data[$this->username_method];

        $register = $this->user_create([
            'name' => _t('anonymous'),
            'password' => Hash::make($data['password']),
        ], [
            $this->username_method => $username,
        ]);

        if($this->username_method == 'email')
        {
            $token = md5(time() . $username . rand());
            $this->userSocialNetwork::create([
                'user_id'             => $register->id,
                'social_network'      => 'email',
                'social_network_user' => $username,
                'token'               => $token
            ]);
            \Session::flash('registerMsg', _t('Check your email!'));
            dispatch(new \iLaravel\Core\Vendor\Jobs\SendEmail('emails.verify', ['email' => $username, 'token' => $token, 'title' => _t('register.complate')]));
        }
        return $register;
    }
}
