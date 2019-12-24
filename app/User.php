<?php

namespace App;

use App\Notifications\VerifyUserNotification;
use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $remember_token
 * @property string $construction_contracts
*/


class User extends Authenticatable implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, Notifiable, HasApiTokens, HasMediaTrait, Auditable;

    public $table = 'users';

    protected $appends = [
        'img_user',
        'signature',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    const GENDER_SELECT = [
        'Male'   => 'Male',
        'Female' => 'Female',
    ];

    protected $dates = [
        'dob',
        'created_at',
        'updated_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'dob',
        'name',
        'email',
        'gender',
        'team_id',
        'password',
        'approved',
        'workphone',
        'created_at',
        'updated_at',
        'deleted_at',
        'jobtitle_id',
        'remember_token',
        'email_verified_at',
    ];

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::created(function (User $user) {
            $registrationRole = config('panel.registration_default_role');

            if (!$user->roles()->get()->contains($registrationRole)) {
                $user->roles()->attach($registrationRole);
            }
        });
    }

    public static function boot()
    {
        parent::boot();

        User::observe(new \App\Observers\UserActionObserver);
    }

    

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function issuebyRfas()
    {
        return $this->hasMany(Rfa::class, 'issueby_id', 'id');
    }

    public function assignRfas()
    {
        return $this->hasMany(Rfa::class, 'assign_id', 'id');
    }

    public function commentByRfas()
    {
        return $this->hasMany(Rfa::class, 'comment_by_id', 'id');
    }

    public function informationByRfas()
    {
        return $this->hasMany(Rfa::class, 'information_by_id', 'id');
    }

    public function createByUserRfas()
    {
        return $this->hasMany(Rfa::class, 'create_by_user_id', 'id');
    }

    public function updateByUserRfas()
    {
        return $this->hasMany(Rfa::class, 'update_by_user_id', 'id');
    }

    public function approveByUserRfas()
    {
        return $this->hasMany(Rfa::class, 'approve_by_user_id', 'id');
    }

    public function createByUserTasks()
    {
        return $this->hasMany(Task::class, 'create_by_user_id', 'id');
    }

    public function userUserAlerts()
    {
        return $this->belongsToMany(UserAlert::class);
    }

    public function getImgUserAttribute()
    {
        $file = $this->getMedia('img_user')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }

    public function getDobAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function jobtitle()
    {
        return $this->belongsTo(Jobtitle::class, 'jobtitle_id');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getSignatureAttribute()
    {
        $file = $this->getMedia('signature')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }

    public function construction_contracts()
    {
        return $this->belongsToMany(ConstructionContract::class);
            //->withPivot('user_id','construction_contract_id');
            // ->withTimestamps();
    }
}
