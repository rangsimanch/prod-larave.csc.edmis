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

class Ncr extends Model implements HasMedia
{
    use SoftDeletes;
    use MultiTenantModelTrait;
    use HasMediaTrait;
    use Auditable;

    public const DOCUMENTS_STATUS_SELECT = [
        '2' => 'Reply',
        '3' => 'Accepted and Closed case.',
        '4' => 'Rejected and need further action.',
    ];

    public $table = 'ncrs';

    public static $searchable = [
        'document_number',
    ];

    protected $dates = [
        'acceptance_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'rootcase_image',
        'containment_image',
        'corrective_image',
        'file_attachment',
    ];

    protected $fillable = [
        'construction_contract_id',
        'corresponding_ncn_id',
        'document_number',
        'acceptance_date',
        'root_case',
        'containment_action',
        'corrective',
        'attachment_description',
        'pages_of_attachment',
        'prepared_by_id',
        'contractor_manager_id',
        'documents_status',
        'issue_by_id',
        'construction_specialist_id',
        'related_specialist_id',
        'leader_id',
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

    public function corresponding_ncn()
    {
        return $this->belongsTo(Ncn::class, 'corresponding_ncn_id');
    }

    public function getAcceptanceDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setAcceptanceDateAttribute($value)
    {
        $this->attributes['acceptance_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getReviewDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setReviewDateAttribute($value)
    {
        $this->attributes['review_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
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

    public function getFileAttachmentAttribute()
    {
        return $this->getMedia('file_attachment');
    }

    public function prepared_by()
    {
        return $this->belongsTo(User::class, 'prepared_by_id');
    }

    public function contractor_manager()
    {
        return $this->belongsTo(User::class, 'contractor_manager_id');
    }

    public function issue_by()
    {
        return $this->belongsTo(User::class, 'issue_by_id');
    }

    public function construction_specialist()
    {
        return $this->belongsTo(User::class, 'construction_specialist_id');
    }

    public function related_specialist()
    {
        return $this->belongsTo(User::class, 'related_specialist_id');
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

    
}
