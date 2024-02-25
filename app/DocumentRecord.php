<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class DocumentRecord extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'document_records';

    protected $appends = [
        'file_upload',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'category',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public const CATEGORY_SELECT = [
        'General' => 'ทั่วไป',
        'Legal'   => 'กฎหมาย',
        'Letter'  => 'จดหมาย',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
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
