<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class DailyReport extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'daily_reports';

    protected $appends = [
        'documents',
    ];

    protected $dates = [
        'input_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'receive_date',
        'acknowledge_date',
    ];

    protected $fillable = [
        'input_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'acknowledge',
        'receive_date',
        'receive_by_id',
        'acknowledge_date',
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

    public function getReceiveDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;

    }

    public function setReceiveDateAttribute($value)
    {
        $this->attributes['receive_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;

    }

    public function getAcknowledgeDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;

    }

    public function setAcknowledgeDateAttribute($value)
    {
        $this->attributes['acknowledge_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;

    }
}
