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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'location',
        'due_date',
        'status_id',
        'weather',
        'temperature',
        'wind',
        'created_at',
        'create_by_user_id',
        'construction_contract_id',
        'updated_at',
        'deleted_at',
        'team_id',
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

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');

    }

    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment');

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

    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }
}
