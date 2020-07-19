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

class NonConformanceNotice extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'non_conformance_notices';

    protected $appends = [
        'attachment',
        'file_upload',
    ];

    const STATUS_NCN_SELECT = [
        'Hold'     => 'Hold',
        'Continue' => 'Continue',
    ];

    protected $dates = [
        'submit_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'subject',
        'description',
        'ref_no',
        'submit_date',
        'attachment_description',
        'status_ncn',
        'csc_issuers_id',
        'created_at',
        'cc_srt',
        'cc_pmc',
        'cc_cec',
        'cc_csc',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getSubmitDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSubmitDateAttribute($value)
    {
        $this->attributes['submit_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment');
    }

    public function csc_issuers()
    {
        return $this->belongsTo(User::class, 'csc_issuers_id');
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
