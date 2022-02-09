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

class Ncn extends Model implements HasMedia
{
    use SoftDeletes;
    use MultiTenantModelTrait;
    use HasMediaTrait;
    use Auditable;

    public const DOCUMENTS_STATUS_SELECT = [
        '1' => 'New',
        '2' => 'Reply',
        '3' => 'Accepted and Closed case.',
        '4' => 'Rejected and need further action.',
    ];

    public $table = 'ncns';

    public static $searchable = [
        'title',
        'document_number',
    ];

    protected $appends = [
        'description_image',
        'file_attachment',
    ];

    protected $dates = [
        'issue_date',
        'acceptance_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'construction_contract_id',
        'dept_code_id',
        'title',
        'issue_date',
        'document_number',
        'description',
        'attachment_description',
        'pages_of_attachment',
        'acceptance_date',
        'documents_status',
        'issue_by_id',
        'leader_id',
        'construction_specialist_id',
        'related_specialist_id',
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

    public function getIssueDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setIssueDateAttribute($value)
    {
        $this->attributes['issue_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
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

    public function getFileAttachmentAttribute()
    {
        return $this->getMedia('file_attachment');
    }

    public function getAcceptanceDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setAcceptanceDateAttribute($value)
    {
        $this->attributes['acceptance_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function issue_by()
    {
        return $this->belongsTo(User::class, 'issue_by_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function construction_specialist()
    {
        return $this->belongsTo(User::class, 'construction_specialist_id');
    }

    public function related_specialist()
    {
        return $this->belongsTo(User::class, 'related_specialist_id');
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
