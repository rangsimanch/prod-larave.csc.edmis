<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class SrtExternalAgencyDocument extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;
    use Auditable;

    public const SAVE_FOR_SELECT = [
        'Process' => 'บันทึกเท่านั้น',
        'Closed'  => 'บันทึกและปิด',
    ];

    public const DOCUMENT_TYPE_SELECT = [
        'เอกสารส่งภายใน'  => 'เอกสารส่งภายใน',
        'เอกสารส่งภายนอก' => 'เอกสารส่งภายนอก',
    ];

    public const SPEED_CLASS_SELECT = [
        'ปกติ'       => 'ปกติ',
        'ด่วน'       => 'ด่วน',
        'ด่วนมาก'    => 'ด่วนมาก',
        'ด่วนที่สุด' => 'ด่วนที่สุด',
    ];

    public const OBJECTIVE_SELECT = [
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

    public $table = 'srt_external_agency_documents';

    protected $appends = [
        'file_upload',
    ];

    protected $dates = [
        'incoming_date',
        'close_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'subject',
        'document_type',
        'originator_number',
        'document_number',
        'incoming_date',
        'refer_to',
        'from',
        'to',
        'attachments',
        'description',
        'speed_class',
        'objective',
        'signatory',
        'document_storage',
        'note',
        'close_date',
        'save_for',
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

    public function construction_contracts()
    {
        return $this->belongsToMany(ConstructionContract::class);
    }

    public function getIncomingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setIncomingDateAttribute($value)
    {
        $this->attributes['incoming_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getCloseDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setCloseDateAttribute($value)
    {
        $this->attributes['close_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
