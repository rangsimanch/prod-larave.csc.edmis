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

class Rfa extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'rfas';

    protected $appends = [
        'file_upload_1',
        'document_file_upload',
        'commercial_file_upload',
    ];

    const DOC_COUNT_RADIO = [
        '7'  => '7',
        '14' => '14',
    ];

    const WORKTYPE_SELECT = [
        'Thai' => 'Part A',
        'China' => 'Part B',
    ];

    protected $dates = [
        'final_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'submit_date',
        'target_date',
        'receive_date',
        'process_date',
        'outgoing_date',
        'hardcopy_date',
        'distribute_date',
    ];

    public static $searchable = [
        'title',
        'title_cn',
        'rfa_code',
        'worktype',
        'title_eng',
        'document_number',
    ];

    protected $fillable = [
        'bill',
        'title',
        'note_1',
        'clause',
        'note_4',
        'note_3',
        'note_2',
        'team_id',
        'type_id',
        'title_cn',
        'qty_page',
        'rfa_code',
        'worktype',
        'doc_count',
        'title_eng',
        'assign_id',
        'deleted_at',
        'updated_at',
        'issueby_id',
        'created_at',
        'final_date',
        'submit_date',
        'spec_ref_no',
        'review_time',
        'target_date',
        'action_by_id',
        'receive_date',
        'document_ref',
        'process_date',
        'origin_number',
        'hardcopy_date',
        'outgoing_date',
        'for_status_id',
        'comment_by_id',
        'wbs_level_3_id',
        'wbs_level_4_id',
        'distribute_date',
        'outgoing_number',
        'incoming_number',
        'document_number',
        'create_by_user_id',
        'comment_status_id',
        'information_by_id',
        'document_status_id',
        'contract_drawing_no',
        'document_description',
        'construction_contract_id',
    ];

    public static function boot()
    {
        parent::boot();

        Rfa::observe(new \App\Observers\RfaActionObserver);
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setCreatedByIdAttribute($input)
    {
        $this->attributes['create_by_id'] = $input ? $input : null;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setCreatedByConstructionContractIdAttribute($input)
    {
        $this->attributes['construction_contract_id'] = $input ? $input : null;
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function type()
    {
        return $this->belongsTo(Rfatype::class, 'type_id');
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function wbs_level_3()
    {
        return $this->belongsTo(WbsLevelThree::class, 'wbs_level_3_id');
    }

    public function wbs_level_4()
    {
        return $this->belongsTo(Wbslevelfour::class, 'wbs_level_4_id');
    }

    public function getSubmitDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSubmitDateAttribute($value)
    {
        $this->attributes['submit_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function issueby()
    {
        return $this->belongsTo(User::class, 'issueby_id');
    }

    public function assign()
    {
        return $this->belongsTo(User::class, 'assign_id');
    }

    public function getFileUpload1Attribute()
    {
        return $this->getMedia('file_upload_1');
    }

    public function comment_by()
    {
        return $this->belongsTo(User::class, 'comment_by_id');
    }

    public function information_by()
    {
        return $this->belongsTo(User::class, 'information_by_id');
    }

    public function getReceiveDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setReceiveDateAttribute($value)
    {
        $this->attributes['receive_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function comment_status()
    {
        return $this->belongsTo(RfaCommentStatus::class, 'comment_status_id');
    }

    public function for_status()
    {
        return $this->belongsTo(RfaCommentStatus::class, 'for_status_id');
    }

    public function document_status()
    {
        return $this->belongsTo(RfaDocumentStatus::class, 'document_status_id');
    }

    public function create_by_user()
    {
        return $this->belongsTo(User::class, 'create_by_user_id');
    }

    public function update_by_user()
    {
        return $this->belongsTo(User::class, 'update_by_user_id');
    }

    public function approve_by_user()
    {
        return $this->belongsTo(User::class, 'approve_by_user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getDistributeDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDistributeDateAttribute($value)
    {
        $this->attributes['distribute_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getProcessDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setProcessDateAttribute($value)
    {
        $this->attributes['process_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getOutgoingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setOutgoingDateAttribute($value)
    {
        $this->attributes['outgoing_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
    
    public function action_by()
    {
        return $this->belongsTo(User::class, 'action_by_id');
    }

    public function getFinalDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFinalDateAttribute($value)
    {
        $this->attributes['final_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
    public function getCommercialFileUploadAttribute()
    {
        return $this->getMedia('commercial_file_upload');
    }

    public function getDocumentFileUploadAttribute()
    {
        return $this->getMedia('document_file_upload');
    }

    public function getTargetDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTargetDateAttribute($value)
    {
        $this->attributes['target_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getHardcopyDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setHardcopyDateAttribute($value)
    {
        $this->attributes['hardcopy_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
