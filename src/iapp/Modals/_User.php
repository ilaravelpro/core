<?php

namespace iLaravel\Core\iApp\Modals;

use iLaravel\Core\iApp\File;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


use Laravel\Passport\HasApiTokens;

class _User extends Authenticatable
{
    use Modal;
    use Notifiable;
    use HasApiTokens;

    protected $guarded = [
        'id', 'remember_token'
    ];

    public static $s_prefix = 'IU';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $appends = ['fullname'];

    protected $hidden = [
        'password', 'remember_token', 'scopes'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
        $this->attributes['fullname'] = $this->attributes['name']." ".$this->attributes['family'];
        return $this->attributes['fullname'];
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
}
