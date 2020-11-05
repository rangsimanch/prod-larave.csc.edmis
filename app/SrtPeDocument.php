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

class SrtPeDocument extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'srt_pe_documents';

    protected $appends = [
        'file_upload',
    ];

    protected $dates = [
        'process_date',
        'finished_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const SAVE_FOR_SELECT = [
        'Save'           => 'บันทึกเท่านั้น',
        'Save and Close' => 'บันทึกและปิด',
    ];

    protected $fillable = [
        'refer_documents_id',
        'process_date',
        'special_command',
        'finished_date',
        'practice_notes',
        'note',
        'created_at',
        'save_for',
        'updated_at',
        'deleted_at',
        'team_id',
        'construction_contract_id',
    ];

    const SPECIAL_COMMAND_SELECT = [
        'Record'               => 'บันทึกงาน',
        'Approve'              => 'อนุมัติดำเนินการ',
        'Non-Approve'          => 'ไม่อนุมัติ',
        'Command'              => 'สั่งการ',
        'Request more details' => 'ขอรายละเอียดเพิ่มเติม',
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

    public function refer_documents()
    {
        return $this->belongsTo(SrtInputDocument::class, 'refer_documents_id');
    }

    public function getProcessDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setProcessDateAttribute($value)
    {
        $this->attributes['process_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getFinishedDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFinishedDateAttribute($value)
    {
        $this->attributes['finished_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function operators()
    {
        return $this->belongsToMany(User::class);
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
