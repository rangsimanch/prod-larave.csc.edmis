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

class Complaint extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'complaints';

    protected $appends = [
        'file_attachment_create',
        'progress_file',
    ];

    const STATUS_SELECT = [
        '1' => 'New',
        '2' => 'In progress/Monitor',
        '3' => 'Closed',
    ];

    protected $dates = [
        'created_at',
        'received_date',
        'action_date',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'status',
        'construction_contract_id',
        'created_at',
        'document_number',
        'complaint_recipient_id',
        'received_date',
        'source_code',
        'complainant',
        'complainant_tel',
        'complainant_detail',
        'complaint_description',
        'type_code',
        'impact_code',
        'operator_id',
        'action_detail',
        'action_date',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    const TYPE_CODE_SELECT = [
        '01' => 'ไม่เกี่ยวข้องกับโครงการ',
        '02' => 'สอบถามข้อมูล',
        '03' => 'ร้องเรียนกิจกรรมก่อสร้าง',
        '04' => 'ร้องเรียนรายละเอียดโครงการ',
        '05' => 'ข้อเรียกร้อง',
        '06' => 'ข้อเสนอแนะ',
    ];

    const SOURCE_CODE_SELECT = [
        '01' => 'สายด่วน (Call Center)',
        '02' => 'สื่อออนไลน์ (Facebook)',
        '03' => 'ส่วนงาน (PR/CR)',
        '04' => 'วิศวกร/เจ้าหน้าที่หน้างาน (Construction Site)',
        '05' => 'หน่วยงานราชการ/ท้องถิ่น (Government Sector)',
        '06' => 'บุคคลที่ 3 (Third Party)',
        '07' => 'จดหมายอิเล็กทรอนิกส์ (Email)',
    ];

    const IMPACT_CODE_SELECT = [
        '01' => 'เสียง',
        '02' => 'คุณภาพอากาศ/ฝุ่นละออง',
        '03' => 'ความสั่นสะเทือน',
        '04' => 'ความไม่สะดวก',
        '05' => 'ความปลอดภัยในชีวิตและทรัพย์สิน',
        '06' => 'การปิดเบี่ยง/การจัดการจราจร',
        '07' => 'สภาพการคมนาคม/ผิวจราจร',
        '08' => 'ขยะมูลฝอย/เศษวัสดุ',
        '09' => 'การระบายน้ำ',
        '10' => 'การพัฒนาโครงการ',
        '11' => 'อื่นๆ',
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

    public function complaint_recipient()
    {
        return $this->belongsTo(Team::class, 'complaint_recipient_id');
    }

    public function getReceivedDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setReceivedDateAttribute($value)
    {
        $this->attributes['received_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getFileAttachmentCreateAttribute()
    {
        return $this->getMedia('file_attachment_create');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function getProgressFileAttribute()
    {
        return $this->getMedia('progress_file');
    }

    public function getActionDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setActionDateAttribute($value)
    {
        $this->attributes['action_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
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
