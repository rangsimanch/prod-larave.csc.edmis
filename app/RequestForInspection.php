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

class RequestForInspection extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'request_for_inspections';

    protected $appends = [
        'files_upload',
        'loop_file_upload',
    ];

    protected $dates = [
        'submittal_date',
        'replied_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const TYPE_OF_WORK_SELECT = [
        'Bored Pile'         => 'Bored Pile',
        'Caisson Foundation' => 'Caisson Foundation',
        'Gravity Wall'       => 'Gravity Wall',
        'Piers'              => 'Piers',
        'Pile Cap'           => 'Pile Cap',
        'Retaining Wall'     => 'Retaining Wall',
    ];

    protected $fillable = [
        'construction_contract_id',
        'wbs_level_1_id',
        'bill_id',
        'wbs_level_3_id',
        'item_1_id',
        'item_2_id',
        'item_3_id',
        'type_of_work',
        'subject',
        'ref_no',
        'location',
        'requested_by_id',
        'submittal_date',
        'contact_person_id',
        'replied_date',
        'ipa',
        'comment',
        'created_at',
        'end_loop',
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

    public function wbs_level_1()
    {
        return $this->belongsTo(WbsLevelOne::class, 'wbs_level_1_id');
    }

    public function bill()
    {
        return $this->belongsTo(BoQ::class, 'bill_id');
    }

    public function wbs_level_3()
    {
        return $this->belongsTo(WbsLevelThree::class, 'wbs_level_3_id');
    }

    public function item_1()
    {
        return $this->belongsTo(BoqItem::class, 'item_1_id');
    }

    public function item_2()
    {
        return $this->belongsTo(BoqItem::class, 'item_2_id');
    }

    public function item_3()
    {
        return $this->belongsTo(BoqItem::class, 'item_3_id');
    }

    public function requested_by()
    {
        return $this->belongsTo(User::class, 'requested_by_id');
    }

    public function getSubmittalDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSubmittalDateAttribute($value)
    {
        $this->attributes['submittal_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function contact_person()
    {
        return $this->belongsTo(User::class, 'contact_person_id');
    }

    public function getRepliedDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setRepliedDateAttribute($value)
    {
        $this->attributes['replied_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getFilesUploadAttribute()
    {
        return $this->getMedia('files_upload');
    }

    public function getLoopFileUploadAttribute()
    {
        return $this->getMedia('loop_file_upload');
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
