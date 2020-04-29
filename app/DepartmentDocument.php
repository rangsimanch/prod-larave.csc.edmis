<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class DepartmentDocument extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'department_documents';

    public static $searchable = [
        'document_name',
    ];

    protected $appends = [
        'download',
        'example_file',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'document_name',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);

    }

    public function getDownloadAttribute()
    {
        return $this->getMedia('download');

    }

    public function getExampleFileAttribute()
    {
        return $this->getMedia('example_file');

    }

    public function tags()
    {
        return $this->belongsToMany(DocumentTag::class);

    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');

    }
}
