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

class RequestForInformation extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'request_for_informations';

    protected $appends = [
        'attachment_files',
        'file_upload',
    ];

    protected $dates = [
        'date',
        'incoming_date',
        'outgoing_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const DOCUMENT_STATUS_SELECT = [
        '1' => 'New',
        '2' => 'Done (CSC Reviewed)',
        '3' => 'Refer to SRT',
        '4' => 'Done (SRT Reviewed)',
    ];

    const SAVE_FOR_SELECT = [
        'Save and Close' => 'บันทึกเพื่อปิด',
        'Save'           => 'บันทึกเพื่อส่งเรื่องต่อไปยัง SRT',
    ];

    protected $fillable = [
        'document_status',
        'to_organization',
        'attention_name',
        'construction_contract_id',
        'title',
        'document_no',
        'originator_code',
        'date',
        'to_id',
        'wbs_level_4_id',
        'wbs_level_5_id',
        'discipline',
        'originator_name',
        'cc_to',
        'incoming_no',
        'incoming_date',
        'description',
        'attachment_file_description',
        'request_by_id',
        'outgoing_date',
        'outgoing_no',
        'authorised_rep_id',
        'response_organization_id',
        'response_date',
        'record',
        'response',
        'created_at',
        'save_for',
        'updated_at',
        'deleted_at',
        'team_id',
        'document_type_id',

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

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function to()
    {
        return $this->belongsTo(Team::class, 'to_id');
    }

    public function wbs_level_4()
    {
        return $this->belongsTo(WbsLevelThree::class, 'wbs_level_4_id');
    }

    public function wbs_level_5()
    {
        return $this->belongsTo(Wbslevelfour::class, 'wbs_level_5_id');
    }

    public function getIncomingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setIncomingDateAttribute($value)
    {
        $this->attributes['incoming_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getAttachmentFilesAttribute()
    {
        return $this->getMedia('attachment_files');
    }

    public function request_by()
    {
        return $this->belongsTo(User::class, 'request_by_id');
    }

    public function getOutgoingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setOutgoingDateAttribute($value)
    {
        $this->attributes['outgoing_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function authorised_rep()
    {
        return $this->belongsTo(User::class, 'authorised_rep_id');
    }

    public function response_organization()
    {
        return $this->belongsTo(Team::class, 'response_organization_id');
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function document_type()
    {
        return $this->belongsTo(Rfatype::class, 'document_type_id');
    }


    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }
}
