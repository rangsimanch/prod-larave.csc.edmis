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

class NonConformanceReport extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'non_conformance_reports';

    protected $appends = [
        'attachment',
        'file_upload',
    ];

    protected $dates = [
        'response_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const CSC_DISPOSITION_STATUS_SELECT = [
        'Agreed'   => 'Agreed',
        'Required' => 'Required additional details',
    ];

    const CSC_CONSIDERATION_STATUS_SELECT = [
        'Agreed'   => 'Agreed',
        'Required' => 'Required additional details',
    ];

    protected $fillable = [
        'ncn_ref_id',
        'corresponding_to',
        'response_date',
        'root_cause',
        'corrective_action',
        'preventive_action',
        'ref_no',
        'prepared_by_id',
        'contractors_project_id',
        'csc_consideration_status',
        'approved_by_id',
        'csc_disposition_status',
        'created_at',
        'construction_contract_id',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function ncn_ref()
    {
        return $this->belongsTo(NonConformanceNotice::class, 'ncn_ref_id');
    }

    public function getResponseDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setResponseDateAttribute($value)
    {
        $this->attributes['response_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment');
    }

    public function prepared_by()
    {
        return $this->belongsTo(User::class, 'prepared_by_id');
    }

    public function contractors_project()
    {
        return $this->belongsTo(User::class, 'contractors_project_id');
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
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
