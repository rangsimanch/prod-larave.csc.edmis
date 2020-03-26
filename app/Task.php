<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Task extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'tasks';

    protected $appends = [
        'attachment',
    ];

    protected $dates = [
        'due_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const WEATHER_SELECT = [
        'Sunny'     => 'Sunny',
        'Cloudy'    => 'Cloudy',
        'Rainy'     => 'Rainy',
        'Stormy'    => 'Stormy',
        'Windy'     => 'Windy',
        'Foggy'     => 'Foggy',
        'Clear sky' => 'Clear sky',
    ];

    protected $fillable = [
        'name',
        'weather',
        'team_id',
        'location',
        'due_date',
        'end_date',
        'status_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'description',
        'temperature',
        'create_by_user_id',
        'construction_contract_id',
    ];

    public static function boot()
    {
        parent::boot();
        Task::observe(new \App\Observers\TaskActionObserver);

    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);

    }

    public function tags()
    {
        return $this->belongsToMany(TaskTag::class);

    }

    public function getDueDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;

    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;

    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;

    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;

    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');

    }

    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment')->last();

    }

    public function create_by_user()
    {
        return $this->belongsTo(User::class, 'create_by_user_id');

    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');

    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');

    }
}
