<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/16/20, 8:23 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Modals;

use iLaravel\Core\iApp\File;
use iLaravel\Core\iApp\UserMeta;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


use Laravel\Passport\HasApiTokens;

class _User extends Authenticatable
{
    use Modal;
    use Metable;
    use Notifiable;
    use HasApiTokens;

    protected $guarded = [
        'id', 'remember_token'
    ];

    public static $s_prefix = 'IU';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    public $metaClass = UserMeta::class;
    public $metaTable = 'user_meta';

    protected $appends = ['fullname'];

    protected $hidden = [
        'password', 'remember_token', 'scopes'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public $with = ['mobile', 'email'];
    protected static function boot()
    {
        parent::boot();
        parent::saving(function (self $event) {
            if (isset($event->mobile)){
                $event->mobile = is_array($event->mobile) ? $event->mobile : $event->mobile->toArray();
                list($country, $number) = [(int) _get_value($event->mobile, 'country'), (int) _get_value($event->mobile, 'number')];
                if ($mobile = $event->mobile()->first()) {
                    if (($has_country = _has_key($event->mobile, 'country') && $mobile->country != $country) || ($has_number = _has_key($event->mobile, 'number') && $mobile->number != $number)){
                        if ($has_country) $mobile->country  = $country;
                        if ($has_number) $mobile->number  = $number;
                        $mobile->verified_at  = null;
                        $mobile->save();
                    }
                }else{
                    $event->mobile()->create([
                        'model' => 'User',
                        'model_id' => $event->id,
                        'key' => 'mobile',
                        'country' => $country,
                        'number' => $number
                    ]);
                }
                unset($event->mobile);
            }
            if (isset($event->email)){
                $event->email = is_array($event->email) ? $event->email : $event->email->toArray();
                list($name, $domain) = [_get_value($event->email, 'name'), _get_value($event->email, 'domain')];
                if ($email = $event->email()->first()) {
                    if (($has_name = _has_key($event->email, 'name') && $email->name != $name) || ($has_domain = _has_key($event->email, 'domain') && $email->domain != $domain)){
                        if ($has_name) $email->name  = $name;
                        if ($has_domain) $email->domain  = $domain;
                        $email->verified_at  = null;
                        $email->save();
                    }
                }else{
                    $event->email()->create([
                        'model' => 'User',
                        'model_id' => $event->id,
                        'key' => 'email',
                        'name' => $name,
                        'domain' => $domain
                    ]);
                }
                unset($event->email);
            }
        });
    }

    public function sendPasswordResetNotification($token)
    {
        dispatch(new \Majazeh\Dashboard\Jobs\SendEmail('emails.recovery', ['email' => $this->email, 'token' => $token, 'title' => _t('change.password.verify.code')]));
    }

    public function getGroupsAttribute()
    {
        return isset($this->original['groups']) ? explode('|', $this->original['groups']) : null;
    }

    public function getAvatarAttribute()
    {
        return File::where('post_id', $this->original['avatar_id'])->get()->keyBy('mode');
    }

    public function getLocationTextAttribute(){
        return $this->original['location'];
    }

    public function getFullnameAttribute()
    {
        return $this->name." ".$this->family;
    }

    public function scopes() {
        return $this->hasMany(imodal('UserScope'));
    }

    public function scopeAll() {
        $role = imodal('Role');
        $role = $role::findByName($this->role);
        $roleScopes = $role ? $role->scopes : [];
        $scopes = $this->scopes->merge($roleScopes);
        return $scopes->where('can', 1)->pluck('scope')->toArray();
    }

    public static function guest()
    {
        return new static([
            'id' => 0,
            'type' => 'guest'
        ]);
    }

    public function mobile() {
        return $this->hasOne(imodal('Phone'), 'model_id')->where('model', 'User')->where('key', 'mobile');
    }

    public function email() {
        return $this->hasOne(imodal('Email'), 'model_id')->where('model', 'User')->where('key', 'email');
    }

    public static function findTokenID($token) {
        return (new \Lcobucci\JWT\Parser())->parse($token)->getHeader('jti');
    }

    public function revokeAllTokens() {
        foreach($this->tokens as $token) {
            $token->revoke();
        }
    }
}
