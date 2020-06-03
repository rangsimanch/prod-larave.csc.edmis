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

class CheckList extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'check_lists';

    protected $appends = [
        'file_upload',
    ];

    protected $dates = [
        'date_of_work_done',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const THAI_OR_CHINESE_WORK_SELECT = [
        'Thai'    => 'Thai',
        'Chinese' => 'Chinese',
    ];

    protected $fillable = [
        'work_type_id',
        'work_title',
        'location',
        'date_of_work_done',
        'name_of_inspector_id',
        'thai_or_chinese_work',
        'construction_contract_id',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function work_type()
    {
        return $this->belongsTo(TaskTag::class, 'work_type_id');
    }

    public function getDateOfWorkDoneAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfWorkDoneAttribute($value)
    {
        $this->attributes['date_of_work_done'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function name_of_inspector()
    {
        return $this->belongsTo(User::class, 'name_of_inspector_id');
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
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
