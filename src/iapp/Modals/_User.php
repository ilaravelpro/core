<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/2/21, 8:25 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use App\User;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\iApp\Role;
use iLaravel\Core\iApp\UserMeta;
use iLaravel\Core\Vendor\Validations\iPhone;
use iLaravel\Core\Vendor\iRole\iRole;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;
use Lcobucci\JWT\Encoding\JoseEncoder;


class _User extends Authenticatable
{
    use Modal;
    use \iLaravel\Core\iApp\Methods\Metable;
    use Notifiable;
    use HasApiTokens;

    protected $guarded = [
        'id', 'remember_token'
    ];

    public static $s_prefix = 'IU';
    public static $s_start = 24300000;
    public static $s_end = 728999999;
    protected $table = 'users';
    public $metaClass = UserMeta::class;
    public $metaTable = 'user_meta';
    public $metaExplodes = ['email', 'mobile'];
    public $hideMeta = true;
    public $_mobile = [];
    public $_email = [];
    public $files = ['avatar'];

    protected $appends = ['fullname', 'fullnamerole'];

    protected $hidden = [
        'password', 'remember_token', 'scopes'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public $with = ['mobile', 'email'];

    public function __construct(array $attributes = [])
    {
        if (count($attributes)) {
            if (isset($attributes['mobile'])) {
                $this->_mobile = $attributes['mobile'];
                unset($this->mobile);
                unset($attributes['mobile']);
            }
            if (isset($attributes['email'])) {
                $this->_email = $attributes['email'];
                unset($this->email);
                unset($attributes['email']);
            }
        }
        parent::__construct($attributes);
    }

    protected static function booting()
    {
        parent::booting();
        parent::saving(function ($event) {
            $event->saveFiles($event->files, Request::createFrom(request()));
        });
        parent::saved(function (self $event) {
            $event->saveMobile($event->_mobile)->saveEmail($event->_email);
            $event->_mobile = [];
            $event->_email = [];
        });
        static::deleted(function (self $event) {
            $event->mobile()->delete();
            $event->email()->delete();
        });
    }

    public function saveMobile($mobile, $verified_at = null) {
        try {
            if ($mobile && isset($mobile) && (is_string($mobile) || (is_array($mobile) && count($mobile)))) {
                if (is_string($mobile) && strlen($mobile) <= 11) {
                    $mobile = "98". ltrim($mobile, '0');
                }
                $mobile = iPhone::parse($mobile);
                $mobile = is_array($mobile) ? $mobile : $this->mobile->toArray();
                unset($mobile['full']);
                if ($mobile) {
                    if ($mobileModel = $this->mobile()->first()) {
                        foreach ($mobile as $index => $item)
                            $mobileModel->$index = $item;
                        $mobileModel->verified_at = $verified_at;
                        $mobileModel->save();
                    } else {
                        $this->mobile()->create(array_merge(
                            [
                                'model' => 'User',
                                'model_id' => $this->id,
                                'key' => 'mobile',
                                'verified_at' => $verified_at,
                            ], $mobile));
                    }
                }
            }
        }catch (\Throwable $exception) {}
        return $this;
    }

    public function saveEmail($email, $verified_at = null) {
        try {
            if ($email && isset($email) && (is_string($email) || (is_array($email) && count($email)))) {
                if (!is_string($email)) $email = is_array($email) ? $email : $this->email->toArray();
                list($name, $domain) = is_string($email) ? explode('@', $email) : [_get_value($email, 'name'), _get_value($email, 'domain')];
                if ($emailModel = $this->email()->first()) {
                    $has_name = $name && $emailModel->name != $name;
                    $has_domain = $domain && $emailModel->domain != $domain;
                    if ($has_name || $has_domain) {
                        if (isset($has_name) && $has_name) $emailModel->name = $name;
                        if (isset($has_domain) && $has_domain) $emailModel->domain = $domain;
                        $emailModel->verified_at = $verified_at;
                        $emailModel->save();
                    }
                } else {
                    $this->email()->create([
                        'model' => 'User',
                        'model_id' => $this->id,
                        'key' => 'email',
                        'name' => $name,
                        'domain' => $domain,
                        'verified_at' => $verified_at,
                    ]);
                }
            }
        }catch (\Throwable $exception) {}
    }

    public function sendPasswordResetNotification($token)
    {
        dispatch(new \iLaravel\Jobs\SendEmail('emails.recovery', ['email' => $this->email, 'token' => $token, 'title' => _t('change.password.verify.code')]));
    }

    public function getAvatarAttribute()
    {
        return $this->getFile('avatar');
    }

    public function getGroupsAttribute()
    {
        return isset($this->original['groups']) ? explode('|', $this->original['groups']) : null;
    }

    public function getLocationTextAttribute()
    {
        return $this->original['location'];
    }

    public function getFullnameAttribute()
    {
        return implode(' ',array_filter([$this->name, $this->family], 'strlen')) ? : null;
    }


    public function getFullnameroleAttribute()
    {
        return auth()->check() && auth()->user()->role !== "user" ? (implode(' ',array_filter([$this->fullname, "(" . _t($this->role) . ")"], 'strlen')) ? : null) : $this->fullname;
    }

    public function getIsAdminAttribute()
    {
        return in_array($this->role, ipreference('admins', ['admin']));
    }

    public function scopes()
    {
        return $this->hasMany(imodal('UserScope'));
    }

    public function scopeAll()
    {
        $role_scopes = ipreference('core.irole.cache.'.$this->role . '.scopes', ['time' => 0, 'data' => []], 'db');
        if (!isset($role_scopes['data']) || (isset($role_scopes['data'])  && count($role_scopes['data']) == 0)) {
            if (in_array($this->role, ipreference('admins'))) {
                $scopes = iRole::scopes(collect(), imodal('RoleScope'), 1)->pluck('can', 'scope')->toArray();
            } else{
                $role = imodal('Role');
                $role = $role::findByName($this->role);
                $roleScopes = $role ? iRole::scopes($role->scopes, imodal('RoleScope')) : [];
                $scopes = $this->scopes->merge($roleScopes);
                $scopes = $scopes->pluck('can', 'scope')->toArray();
            }
            upreference('core.irole.cache.'.$this->role . '.scopes', ['time' => time(), 'data' => $scopes]);
            return $scopes;
        }else {
            return $role_scopes['data'];
        }
    }

    public function scopeAllUnique()
    {
        $scopes = [];
        $subs = array_reverse(array_unique(ipreference('scopeSubs')), true);
        foreach ($this->scopeAll() as $scope => $can)
            if (!isset($scopes[$scopeNew = trim(str_replace($subs, '', $scope), '.')]) || $can) $scopes[$scopeNew] = $can;
        return $scopes;
    }

    public static function guest()
    {
        $user = new static([
            'id' => 0,
            'role' => 'guest',
            'status' => 'active',
        ]);
        $user->setAttribute('id', 0);
        return $user;
    }

    public function mobile()
    {
        return $this->hasOne(imodal('Phone'), 'model_id')->where('model', 'User')->where('key', 'mobile');
    }

    public function email()
    {
        return $this->hasOne(imodal('Email'), 'model_id')->where('model', 'User')->where('key', 'email');
    }

    public static function findTokenID($token)
    {
        return (new \Lcobucci\JWT\Token\Parser(new JoseEncoder()))->parse($token)->claims()->get('jti');
    }

    public static function findByToken($token)
    {
        $token = Token::find(static::findTokenID(trim(str_replace('Bearer', '', $token))));
        return $token ? static::find($token->user_id) : false;
    }

    public function revokeAllTokens()
    {
        foreach ($this->tokens as $token) {
            $token->revoke();
        }
    }

    public function rules($request, $action, $arg = null)
    {
        if ($arg) $arg = is_string($arg) ? $this::findBySerial($arg) : $arg;
        $rules = [];
        $additionalRules = [
            'avatar_file' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120',
        ];
        switch ($action) {
            case 'login':
            case 'register':
                $rules = [
                    'username' => "required",
                    'password' => 'required|min:6|password',
                ];
                break;
            case 'account':
                $rules = [
                    'avatar_file' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120',
                    'name' => 'nullable|string|max:191',
                    'family' => 'nullable|string|max:191',
                    'password' => 'nullable|min:6|password',
                    'website' => "nullable|website",
                    'gender' => 'nullable|in:male,female',
                ];
                if (!$request->password)
                    unset($rules['password']);
                break;
            case 'store':
                $rules = ["creator_id" => "required|exists:users,id"];
            case 'update':
                $rules = array_merge($rules, [
                    'name' => 'nullable|string|max:191',
                    'family' => 'nullable|string|max:191',
                    'username' => "nullable|username",
                    'password' => 'nullable|min:6|password',
                    'email' => "nullable|i_email",
                    'website' => "nullable|website",
                    'status' => 'nullable|in:' . join(',', iconfig('status.users', iconfig('status.global'))),
                    'role' => 'nullable|in:' . ((in_array(auth()->user()->role, ipreference('admins', ['admin'])) ? "admin," : "") . implode(',', Role::all()->pluck('name')->toArray())),
                    'mobile' => 'nullable|mobile',
                    'gender' => 'nullable|in:male,female',
                    'groups' => 'nullable',
                ], $additionalRules);
                if (!$request->password)
                    unset($rules['password']);
                if ($arg == null || (isset($arg->username) && $arg->username != $request->username)) $rules['username'] .= '|unique:users,username';
                if ($arg == null || (isset($arg->website) && $arg->website != $request->website)) $rules['website'] .= '|unique:users,website';
                $rules['mobile'] .= ':unique,User' . ($arg && $arg->mobile ? ",{$arg->id}" : "");
                if ($arg == null || (isset($arg->email) && $arg->email && $arg->email->text != $request->email)) $rules['email'] .= ':unique,User';
                break;
            case 'additional':
                $rules = $additionalRules;
                break;
        }
        return $rules;
    }
}
