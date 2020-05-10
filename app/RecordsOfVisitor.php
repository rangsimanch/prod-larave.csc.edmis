<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class RecordsOfVisitor extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, Auditable;

    public $table = 'records_of_visitors';

    protected $appends = [
        'file_upload',
    ];

    protected $dates = [
        'date_of_visit',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date_of_visit',
        'name_of_visitor',
        'details',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getDateOfVisitAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfVisitAttribute($value)
    {
        $this->attributes['date_of_visit'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
    }
}
