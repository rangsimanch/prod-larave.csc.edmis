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
    ];

    const WORKTYPE_SELECT = [
        'Thai' => 'Thai',
        'China' => 'China',
    ];

    protected $dates = [
        'final_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'submit_date',
        'receive_date',
        'process_date',
        'outgoing_date',
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
        'title',
        'note_4',
        'note_3',
        'note_2',
        'note_1',
        'team_id',
        'type_id',
        'title_cn',
        'rfa_code',
        'final_date',
        'worktype',
        'assign_id',
        'title_eng',
        'issueby_id',
        'deleted_at',
        'updated_at',
        'created_at',
        'submit_date',
        'review_time',
        'receive_date',
        'action_by_id',
        'process_date',
        'for_status_id',
        'comment_by_id',
        'outgoing_date',
        'wbs_level_4_id',
        'wbs_level_3_id',
        'document_number',
        'comment_status_id',
        'information_by_id',
        'create_by_user_id',
        'update_by_user_id',
        'incoming_number',
        'outgoing_number',
        'distribute_date',
        'document_status_id',
        'approve_by_user_id',
        'check_revision',
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
}
