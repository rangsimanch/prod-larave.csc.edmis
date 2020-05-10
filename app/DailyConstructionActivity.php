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

class DailyConstructionActivity extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    protected $appends = [
        'image_upload',
    ];

    public $table = 'daily_construction_activities';

    protected $dates = [
        'operation_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'work_title',
        'operation_date',
        'construction_contract_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getOperationDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setOperationDateAttribute($value)
    {
        $this->attributes['operation_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getImageUploadAttribute()
    {
        $files = $this->getMedia('image_upload');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
        });

        return $files;
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
