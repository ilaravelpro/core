<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\IApp\Http\Controllers\API\v1\UserController;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class LoginController extends AuthController
{
    public $endpoint = UserController::class;
    use AuthenticatesUsers {
        login as auth_login;
    }
    public $views = [
        'login' => 'auth.login'
    ];
    public $loginTo = "login";

    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');
        parent::__construct($request);
    }

    public function login(Request $request)
    {
        $this->username_method($request);
        if($request->has('reset'))
        {
            if(!config('auth.enter.recovery', true))
            {
                throw ValidationException::withMessages([
                    $this->username() => [_t('auth.reset.disabled')],
                ]);
            }
            $request->request->add(['email' => $request->username]);
            return (new ForgotPasswordController($request))->sendResetLinkEmail($request);

            $check = $this->credentials($request);
            unset($check['password']);
            $user = config('auth.providers.users.model')::where($check)->first();
            if(!$user)
            {
                throw ValidationException::withMessages([
                    $this->username() => [_t('auth.failed')],
                ]);
            }
            // $username = $request->input($this->username_method);
            // dispatch(new \Majazeh\Baravel\Jobs\SendEmail('emails.verify', ['email' => $username, 'token' => $token]));
            // \Session::flash('registerMsg', _t('Check your email!'));
            return $this->showLoginForm();
        }
        if(!config('auth.enter.login', true))
        {
            throw ValidationException::withMessages([
                $this->username() => [_t('auth.login.disabled')],
            ]);
        }
        return $this->auth_login($request);
    }

    protected function attemptLogin(Request $request)
    {
        $username = $request->input($this->username_method);
        if(substr($username, 0, 1) == '.')
        {
            $request->request->add([$this->username_method => substr($username, 1)]);
            \Config::set('database.connections._mysql.database', config('database.connections.mysql.database'));
            \Config::set('database.connections._mysql.username', config('database.connections.mysql.username'));
            \Config::set('database.connections._mysql.password', config('database.connections.mysql.password'));
            \Config::set('database.connections.mysql.database', config('database.connections.test.database'));
            \Config::set('database.connections.mysql.username', config('database.connections.test.username'));
            \Config::set('database.connections.mysql.password', config('database.connections.test.password'));
            \DB::reconnect('mysql');
        }
        else if($request->session()->get('dev'))
        {
            $request->session()->forget('dev');
        }
        $guard = $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
        if(!$guard)
        {
            return $this->sendFailedLoginResponse($request);
        }
        session(['api-token' => auth()->user()->createToken('api-service')->accessToken]);
        return $guard;
    }

    /**
     * @return username field in html form
     */
    public function username()
    {
        return $this->username_method;
    }

    /**
     * @return resources blade view for form login
     */
    public function showLoginForm()
    {
        return $this->view(request());
    }

    /**
     * @param  Request request
     * @return query where for find valid user if status not active user has not permision for login
     */
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['status' => 'active']);
    }

    /**
     * @return google login url
     */
    public function redirectToProvider()
    {
        return \Socialite::driver('google')->redirect();
    }

    /**
     * @return google login callback
     */
    public function handleProviderCallback(Request $request)
    {
        try {
            $user = \Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect($this->loginTo);
        }
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            if($existingUser->google_id != $user->id)
            {
                $existingUser->google_id = $user->id;
                $existingUser->avatar = $user->avatar;
                $existingUser->save();
            }
            $this->guard()->setUser($existingUser);
            if($existingUser->status != 'active')
            {
                return $this->sendFailedLoginResponse($request);
            }
            $this->guard()->login($existingUser, true);
            return $this->sendLoginResponse($request);
        } else {
            $this->social_media_register($user);
        }
        return redirect()->to($this->redirectTo);
    }

    /**
     * @param  Request request
     * chack fail login, if user is not active or not valid make exeption and if user not found run register controller
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // check if user login with social media
        $user = false;
        if($this->guard()->user())
        {
            $user = $this->guard()->user();
        }
        else
        {
            $user = $this->model::where($this->username(), $request->{$this->username()})->first();
        }
        $UserModel = config('auth.providers.users.model');
        // if user exists
        if($user)
        {
            $check_password = Hash::check($request->password, $user->password);

            if($user->status != 'active' &&
                (($request->password && $check_password) || !$request->password)
            )
            {
                throw ValidationException::withMessages([
                    $this->username() => [_t('auth.activefailed')],
                ]);
            }
            throw ValidationException::withMessages([
                $this->username() => [_t('auth.failed')],
            ]);
        }
        elseif(config('auth.enter.auto_register', true) || !$UserModel::count())
        {
            $register = new RegisterController();
            $register->username_method = $this->username_method;
            return $register->register($request);
        }
        else {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
    }

    public function emailVerify($token){
        $token = $this->userSocialNetwork::where('token', $token)->where('verify', 'waiting')->first();
        if($token)
        {
            $token->verify = 'verified';
            $token->save();
            $token->user->status = 'active';
            $token->user->save();
            return redirect()->to($this->loginTo);
        }
    }
}
