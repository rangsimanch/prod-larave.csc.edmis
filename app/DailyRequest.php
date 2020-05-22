<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class DailyRequest extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, Auditable;

    public $table = 'daily_requests';

    protected $appends = [
        'documents',
    ];

    protected $dates = [
        'input_date',
        'acknowledge_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'input_date',
        'document_code',
        'receive_by_id',
        'acknowledge_date',
        'constuction_contract_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getInputDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setInputDateAttribute($value)
    {
        $this->attributes['input_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDocumentsAttribute()
    {
        return $this->getMedia('documents');
    }

    public function receive_by()
    {
        return $this->belongsTo(User::class, 'receive_by_id');
    }

    public function getAcknowledgeDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setAcknowledgeDateAttribute($value)
    {
        $this->attributes['acknowledge_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function constuction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'constuction_contract_id');
    }

    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }
}
