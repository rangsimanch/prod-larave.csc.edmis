<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class AddDrawing extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'add_drawings';

    protected $appends = [
        'file_upload',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'drawing_title',
        'activity_type_id',
        'work_type_id',
        'coding_of_drawing',
        'location',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function activity_type()
    {
        return $this->belongsTo(DownloadSystemActivity::class, 'activity_type_id');
    }

    public function work_type()
    {
        return $this->belongsTo(DownloadSystemWork::class, 'work_type_id');
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
