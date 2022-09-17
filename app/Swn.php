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

class Swn extends Model implements HasMedia
{
    use SoftDeletes;
    use MultiTenantModelTrait;
    use HasMediaTrait;
    use Auditable;

    public const REPLY_NCR_SELECT = [
        'Yes' => 'Yes',
        'No'  => 'No',
    ];

    public const DOCUMENTS_STATUS_SELECT = [
        '1' => 'New',
        '2' => 'Replied',
        '3' => 'Reviewed',
        '4' => 'Done',
        '5' => 'Required addition'
    ];

    public const REVIEW_STATUS_SELECT = [
        '1' => 'Accepted',
        '2' => 'Rejected',
        '3' => 'Conditional Accepted with required addition',
    ];

    public const AUDITING_STATUS_SELECT = [
        '1' => 'Accepted and Closed case.',
        '2' => 'Rejected and need further action.',
    ];

    public $table = 'swns';

    public static $searchable = [
        'title',
        'document_number',
    ];

    protected $dates = [
        'submit_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'document_attachment',
        'description_image',
        'rootcase_image',
        'containment_image',
        'corrective_image',
        'reply_document',
        'conditional_file_upload',
    ];

    protected $fillable = [
        'construction_contract_id',
        'title',
        'dept_code_id',
        'submit_date',
        'review_date',
        'auditing_date',
        'document_number',
        'location',
        'reply_ncr',
        'ref_doc',
        'description',
        'issue_by_id',
        'root_case',
        'containment_action',
        'corrective',
        'responsible_id',
        'related_specialist_id',
        'review_status',
        'construction_specialist_id',
        'leader_id',
        'auditing_status',
        'documents_status',
        'conditional_accepted',
        'created_at',
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

    public function dept_code()
    {
        return $this->belongsTo(Department::class, 'dept_code_id');
    }

    public function getAuditingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setAuditingDateAttribute($value)
    {
        $this->attributes['auditing_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getReviewDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setReviewDateAttribute($value)
    {
        $this->attributes['review_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getSubmitDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSubmitDateAttribute($value)
    {
        $this->attributes['submit_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDocumentAttachmentAttribute()
    {
        return $this->getMedia('document_attachment');
    }

    public function getDescriptionImageAttribute()
    {
        $files = $this->getMedia('description_image');
        $files->each(function ($item) {
            $item->url = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview = $item->getUrl('preview');
        });

        return $files;
    }

    public function issue_by()
    {
        return $this->belongsTo(User::class, 'issue_by_id');
    }

    public function getRootcaseImageAttribute()
    {
        $files = $this->getMedia('rootcase_image');
        $files->each(function ($item) {
            $item->url = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview = $item->getUrl('preview');
        });

        return $files;
    }

    public function getContainmentImageAttribute()
    {
        $files = $this->getMedia('containment_image');
        $files->each(function ($item) {
            $item->url = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview = $item->getUrl('preview');
        });

        return $files;
    }

    public function getCorrectiveImageAttribute()
    {
        $files = $this->getMedia('corrective_image');
        $files->each(function ($item) {
            $item->url = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview = $item->getUrl('preview');
        });

        return $files;
    }

    public function getConditionalFileUploadAttribute()
    {
        return $this->getMedia('conditional_file_upload');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function related_specialist()
    {
        return $this->belongsTo(User::class, 'related_specialist_id');
    }

    public function construction_specialist()
    {
        return $this->belongsTo(User::class, 'construction_specialist_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getReplyDocumentAttribute()
    {
        return $this->getMedia('reply_document');
    }
}
