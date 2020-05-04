<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class ContractAndComponent extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, Auditable;

    protected $appends = [
        'file_upload',
    ];

    public $table = 'contract_and_components';

    public static $searchable = [
        'document_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'document_name',
        'document_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);

    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');

    }
}
