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

class SrtInputDocument extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    const TO_SELECT = [
        'B' => 'B',
    ];

    const FROM_SELECT = [
        'A' => 'A',
    ];

    public $table = 'srt_input_documents';

    protected $appends = [
        'file_upload',
    ];

    protected $dates = [
        'incoming_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const DOCUMENT_TYPE_SELECT = [
        'เอกสารส่งภายใน' => 'เอกสารส่งภายใน',
    ];

    const SPEED_CLASS_SELECT = [
        'ปกติ'       => 'ปกติ',
        'ด่วน'       => 'ด่วน',
        'ด่วนมาก'    => 'ด่วนมาก',
        'ด่วนที่สุด' => 'ด่วนที่สุด',
    ];

    protected $fillable = [
        'document_type',
        'document_number',
        'incoming_date',
        'refer_to',
        'attachments',
        'from',
        'to',
        'description',
        'speed_class',
        'objective',
        'signer',
        'document_storage',
        'note',
        'created_at',
        'docuement_status_id',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    const OBJECTIVE_SELECT = [
        'เพื่อดำเนินการ'                           => 'เพื่อดำเนินการ',
        'เพื่อพิจารณาอนุมัติ'                      => 'เพื่อพิจารณาอนุมัติ',
        'เพื่อพิจารณา'                             => 'เพื่อพิจารณา',
        'เพื่อทราบและพิจารณา'                      => 'เพื่อทราบและพิจารณา',
        'เพื่อพิจารณาดำเนินการต่อไป'               => 'เพื่อพิจารณาดำเนินการต่อไป',
        'เพื่อพิจารณาดำเนินการในส่วนที่เกี่ยวข้อง' => 'เพื่อพิจารณาดำเนินการในส่วนที่เกี่ยวข้อง',
        'เพื่อตรวจสอบและพิจารณาดำเนินการ'          => 'เพื่อตรวจสอบและพิจารณาดำเนินการ',
        'เพื่อพิจารณาและขอทราบ'                    => 'เพื่อพิจารณาและขอทราบ',
        'เพื่อพิจารณาให้เห็นชอบ'                   => 'เพื่อพิจารณาให้เห็นชอบ',
        'เพื่อลงนาม'                               => 'เพื่อลงนาม',
        'เพื่อพิจารณาลงนาม'                        => 'เพื่อพิจารณาลงนาม',
        'เพื่อวินัจฉัยและตีความ'                   => 'เพื่อวินัจฉัยและตีความ',
        'เพื่อพิจารณาและชี้แนะแนวทาง'              => 'เพื่อพิจารณาและชี้แนะแนวทาง',
        'เพื่อทราบและถือปฎิบัติ'                   => 'เพื่อทราบและถือปฎิบัติ',
        'เพื่อโปรดทราบ'                            => 'เพื่อโปรดทราบ',
        'เพื่อทราบ'                                => 'เพื่อทราบ',
        'เพื่อโปรดพิจารณา'                         => 'เพื่อโปรดพิจารณา',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getIncomingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setIncomingDateAttribute($value)
    {
        $this->attributes['incoming_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function docuement_status()
    {
        return $this->belongsTo(SrtDocumentStatus::class, 'docuement_status_id');
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
