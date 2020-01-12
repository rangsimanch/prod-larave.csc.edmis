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

    protected $fillable = [
        'name',
        'team_id',
        'due_date',
        'end_date',
        'status_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'description',
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

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }

    public function tags()
    {
        return $this->belongsToMany(TaskTag::class);
    }

    
    /**
     * Set to null if empty
     * @param $input
     */
    public function setConstractionContractIdAttribute($input)
    {
        $this->attributes['construction_contract_id'] = $input ? $input : null;
    }

    public function getDueDateAttribute($value)
    {
         return $value ? Carbon::parse($value)->format(config('panel.date_format'))  : null;
        //  return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;

    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;

    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
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

    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }
}