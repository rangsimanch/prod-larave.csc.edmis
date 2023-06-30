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

class CloseOutPunchList extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'close_out_punch_lists';

    protected $appends = [
        'files_report_before',
        'files_report_after',
    ];

    public const REVIEW_STATUS_SELECT = [
        '1' => 'Accepted',
        '2' => 'Comment',
    ];

    public const DOCUMENT_STATUS_SELECT = [
        '1' => 'New',
        '2' => 'Done',
        '3' => 'Waiting for Revise',
    ];

    protected $dates = [
        'submit_date',
        'respond_date',
        'review_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'construction_contract_id',
        'subject',
        'document_number',
        'submit_date',
        'sub_location',
        'sub_worktype',
        'respond_date',
        'review_status',
        'review_date',
        'reviewer_id',
        'document_status',
        'created_at',
        'revision_list',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getSubmitDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSubmitDateAttribute($value)
    {
        $this->attributes['submit_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function boqs()
    {
        return $this->belongsToMany(BoQ::class);
    }

    public function locations()
    {
        return $this->belongsToMany(CloseOutLocation::class);
    }

    public function work_types()
    {
        return $this->belongsToMany(CloseOutWorkType::class);
    }

    public function getFilesReportBeforeAttribute()
    {
        return $this->getMedia('files_report_before');
    }

    public function getRespondDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setRespondDateAttribute($value)
    {
        $this->attributes['respond_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getFilesReportAfterAttribute()
    {
        return $this->getMedia('files_report_after');
    }

    public function getReviewDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setReviewDateAttribute($value)
    {
        $this->attributes['review_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
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
