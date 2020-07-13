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

class SiteWarningNotice extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'site_warning_notices';

    protected $appends = [
        'attachment',
        'file_upload',
    ];

    const REPLY_BY_NCR_SELECT = [
        'Yes' => 'Yes',
        'No'  => 'No',
    ];

    const DISPOSITION_STATUS_SELECT = [
        'Accepted' => 'Accepted and Closed case',
        'Rejected' => 'Rejected and need further action',
    ];

    protected $dates = [
        'submit_date',
        'containment_completion_date',
        'corrective_completion_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const REVIEW_AND_JUDGEMENT_STATUS_SELECT = [
        'Accepted'    => 'Accepted',
        'Rejected'    => 'Rejected',
        'Conditional' => 'Conditional Accepted with required addition',
    ];

    protected $fillable = [
        'subject',
        'location',
        'reply_by_ncr',
        'swn_no',
        'submit_date',
        'to_team_id',
        'construction_contract_id',
        'description',
        'issue_by_id',
        'reviewed_by_id',
        'root_cause',
        'containment_actions',
        'containment_responsible_id',
        'containment_completion_date',
        'corrective_and_preventive',
        'corrective_responsible_id',
        'corrective_completion_date',
        'section_2_reviewed_by_id',
        'section_2_approved_by_id',
        'review_and_judgement_status',
        'note',
        'csc_issuer_id',
        'csc_qa_id',
        'disposition_status',
        'csc_pm_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getSubmitDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSubmitDateAttribute($value)
    {
        $this->attributes['submit_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function to_team()
    {
        return $this->belongsTo(Team::class, 'to_team_id');
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function issue_by()
    {
        return $this->belongsTo(User::class, 'issue_by_id');
    }

    public function reviewed_by()
    {
        return $this->belongsTo(User::class, 'reviewed_by_id');
    }

    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment');
    }

    public function containment_responsible()
    {
        return $this->belongsTo(User::class, 'containment_responsible_id');
    }

    public function getContainmentCompletionDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setContainmentCompletionDateAttribute($value)
    {
        $this->attributes['containment_completion_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function corrective_responsible()
    {
        return $this->belongsTo(User::class, 'corrective_responsible_id');
    }

    public function getCorrectiveCompletionDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setCorrectiveCompletionDateAttribute($value)
    {
        $this->attributes['corrective_completion_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function section_2_reviewed_by()
    {
        return $this->belongsTo(User::class, 'section_2_reviewed_by_id');
    }

    public function section_2_approved_by()
    {
        return $this->belongsTo(User::class, 'section_2_approved_by_id');
    }

    public function csc_issuer()
    {
        return $this->belongsTo(User::class, 'csc_issuer_id');
    }

    public function csc_qa()
    {
        return $this->belongsTo(User::class, 'csc_qa_id');
    }

    public function csc_pm()
    {
        return $this->belongsTo(User::class, 'csc_pm_id');
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
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
