<?php

namespace iLaravel\Core\IApp\Http\Controllers\WEB\Controllers\Auth;

use iLaravel\Core\IApp\Http\Controllers\WEB\Controller;
use Intervention\Image\Facades\Image;
use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

class AuthController extends Controller
{
    public $redirectTo = '/';
    public $username_method = null;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->model = imodal('User');
        $this->userSocialNetwork = imodal('UserSocialNetwork');
    }

    public function username_method(Request $request)
    {
        if($this->username_method) return $this->username_method;
        $username = $request->username;
        $type = 'username';
        if(ctype_digit($username)){
            $type = 'mobile';
            $request->request->remove('username');
            $request->request->add([$type => $username]);
        }
        elseif(strpos($username, '@'))
        {
            $type = 'email';
            $request->request->remove('username');
            $request->request->add([$type => $username]);

        }
        $this->username_method = $type;
        return $type;
    }

    public function social_media_register($user)
    {
        $data 				= [];
        $data['name']		= $user->name;
        $data['email']		= $user->email;
        $data['google_id']	= $user->id;
        $data['gender']		= isset($user->user['gender']) && in_array($user->user['gender'], ['male', 'female']) ? $user->user['gender'] : null;
        $data['status']		= 'active';

        $image = Image::make($user->avatar_original);

        if(!is_dir(public_path('storage')))
        {
            mkdir(public_path('storage'));
        }
        if(!file_exists(public_path('/storage/user.txt')))
        {
            $count = 0;
        }
        else
        {
            $count = file_get_contents(public_path('/storage/user.txt'));
        }
        file_put_contents(public_path('/storage/user.txt'), ++$count);
        $folder = intval(ceil($count / 1000));
        $path_name = '/storage/user_avatars-'. str_repeat('0', 5 - strlen($folder)) . $folder;
        $path = public_path($path_name);
        if(!is_dir($path))
        {
            mkdir($path);
        }
        $image->resize(500, 500);
        $name = time().rand(123456789, 999999999);
        $image->encode('jpg', 100);

        $image->save($path . "/$name.jpg");
        $md5 = md5_file($path . "/$name.jpg");
        rename($path . "/$name.jpg", $path . "/$md5.jpg");
        $image->resize(250, 250);
        $image->save($path . "/$md5-250.jpg");
        $image->resize(100, 100);
        $image->save($path . "/$md5-100.jpg");

        $data['avatar']		= asset("$path_name/$md5");

        $newUser 			= $this->user_create($data, $data);
        auth()->login($newUser, true);
    }

    public function user_create($data, $_data = []){
        $new = $this->model::create($data);
        if($this->model::count() === 1)
        {
            $_data['role'] = 'admin';
            $_data['name'] = 'Admin';
            $_data['status'] = 'active';
        }
        if(config('auth.enter.auto_verify'))
        {
            $_data['status'] = 'active';
        }
        if(!empty($_data))
        {
            foreach ($_data as $key => $value) {
                $new->{$key} = $value;
            }
            $new->save();
        }
        return $new;
    }
}
