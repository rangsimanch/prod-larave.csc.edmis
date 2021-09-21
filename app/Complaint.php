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
        '1' => 'อยู่ในระหว่างการแก้ไข',
        '2' => 'ใช้เวลาการแก้ไขน้อยกว่า 30 วัน',
        '3' => 'ใช้เวลาการแก้ไขมากกว่า 30 วัน',

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
        '1' => 'ไม่เกี่ยวข้องกับโครงการ',
        '2' => 'สอบถามข้อมูล',
        '3' => 'ร้องเรียนกิจกรรมก่อสร้าง',
        '4' => 'ร้องเรียนรายละเอียดโครงการ',
        '5' => 'ข้อเรียกร้อง',
        '6' => 'ข้อเสนอแนะ',
    ];

    const SOURCE_CODE_SELECT = [
        '1' => 'สายด่วน (Call Center)',
        '2' => 'สื่อออนไลน์ (Facebook)',
        '3' => 'ส่วนงาน (PR/CR)',
        '4' => 'วิศวกร/เจ้าหน้าที่หน้างาน (Construction Site)',
        '5' => 'หน่วยงานราชการ/ท้องถิ่น (Government Sector)',
        '6' => 'บุคคลที่ 3 (Third Party)',
        '7' => 'จดหมายอิเล็กทรอนิกส์ (Email)',
    ];

    const IMPACT_CODE_SELECT = [
        '1' => 'เสียง',
        '2' => 'คุณภาพอากาศ/ฝุ่นละออง',
        '3' => 'ความสั่นสะเทือน',
        '4' => 'ความไม่สะดวก',
        '5' => 'ความปลอดภัยในชีวิตและทรัพย์สิน',
        '6' => 'การปิดเบี่ยง/การจัดการจราจร',
        '7' => 'สภาพการคมนาคม/ผิวจราจร',
        '8' => 'ขยะมูลฝอย/เศษวัสดุ',
        '9' => 'การระบายน้ำ',
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
