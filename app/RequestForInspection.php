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

    protected $appends = [
        'files_upload',
    ];

    public $table = 'request_for_inspections';

    protected $dates = [
        'inspection_date_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const RESULT_OF_INSPECTION_SELECT = [
        'Accepted'              => 'Accepted',
        'Accepted with Comment' => 'Accepted with Comment',
        'Issue NCN'             => 'Issue NCN',
    ];

    const TYPE_OF_WORK_SELECT = [
        'Civil Works'      => 'Civil Works',
        'Architecture'     => 'Architecture',
        'Building Service' => 'Building Service',
        'Other'            => 'Other',
    ];

    protected $fillable = [
        'bill_no',
        'subject',
        'item_no',
        'ref_no',
        'inspection_date_time',
        'contact_person_id',
        'type_of_work',
        'location',
        'details_of_inspection',
        'ref_specification',
        'requested_by_id',
        'result_of_inspection',
        'comment',
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

    public function getInspectionDateTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;

    }

    public function setInspectionDateTimeAttribute($value)
    {
        $this->attributes['inspection_date_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;

    }

    public function contact_person()
    {
        return $this->belongsTo(User::class, 'contact_person_id');

    }

    public function requested_by()
    {
        return $this->belongsTo(User::class, 'requested_by_id');

    }

    public function getFilesUploadAttribute()
    {
        return $this->getMedia('files_upload');

    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');

    }

    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');

    }
}
