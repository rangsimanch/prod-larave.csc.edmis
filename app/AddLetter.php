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

class AddLetter extends Model implements HasMedia
{
    use SoftDeletes;
    use MultiTenantModelTrait;
    use HasMediaTrait;
    use Auditable;

    public $table = 'add_letters';


    public const SPEED_CLASS_SELECT = [
        'Normal'      => 'ปกติ',
        'Urgent'      => 'ด่วน',
        'Very Urgent' => 'ด่วนมาก',
        'Important'   => 'ด่วนที่สุด',
    ];

    public const LETTER_TYPE_SELECT = [
        'Complaint' => 'หนังสือร้องเรียน',
        'VO'        => 'หนังสือเบิกเงิน (VO)',
        'PS'        => 'หนังสือเบิกเงินสำรองจ่าย (PS)',
        'General'   => 'หนังสือทั่วไป',
    ];

    public const OBJECTIVE_SELECT = [
        'เพื่อทราบ'                                => 'เพื่อทราบ',  // Acknowledged
        'เพื่อดำเนินการ'                           => 'เพื่อดำเนินการ', // Replied
        'เพื่อพิจารณา'                             => 'เพื่อพิจารณา', // Replied
        'เพื่อโปรดทราบ'                            => 'เพื่อโปรดทราบ', // Acknowledged
        'เพื่อพิจารณาดำเนินการต่อไป'               => 'เพื่อพิจารณาดำเนินการต่อไป', // Replied
        'เพื่อพิจารณาดำเนินการในส่วนที่เกี่ยวข้อง' => 'เพื่อพิจารณาดำเนินการในส่วนที่เกี่ยวข้อง', // Replied
        'เพื่อตรวจสอบและพิจารณา'                   => 'เพื่อตรวจสอบและพิจารณา', // Replied
        'เพื่อพิจารณาและขอทราบ'                    => 'เพื่อพิจารณาและขอทราบ', // Replied
        'เพื่อพิจารณาให้เห็นชอบ'                   => 'เพื่อพิจารณาให้เห็นชอบ', // Replied
        'เพื่อลงนาม'                               => 'เพื่อลงนาม', // Replied
        'เพื่อพิจารณาลงนาม'                        => 'เพื่อพิจารณาลงนาม', // Replied
        'เพื่อวินิจฉัยและตีความ'                   => 'เพื่อวินิจฉัยและตีความ', // Replied
        'เพื่อพิจารณาและชี้แนะแนวทาง'              => 'เพื่อพิจารณาและชี้แนะแนวทาง', // Replied
        'เพื่อทราบและถือปฎิบัติ'                   => 'เพื่อทราบและถือปฎิบัติ', // Acknowledged
        'เพื่อทราบและพิจารณา'                      => 'เพื่อทราบและพิจารณา', // Replied
        'เพื่อพิจารณาอนุมัติ'                      => 'เพื่อพิจารณาอนุมัติ', // Replied
        'เพื่อโปรดพิจารณา'                         => 'เพื่อโปรดพิจารณา', // Replied
        'เพื่อโปรดพิจารณาให้ความอนุเคราะห์'          => 'เพื่อโปรดพิจารณาให้ความอนุเคราะห์', // Replied
        'จึงเรียนมาเพื่อโปรดประสานงานผู้ที่เกี่ยวข้องเข้าร่วมตามวันและเวลาดังกล่าว' => 'จึงเรียนมาเพื่อโปรดประสานงานผู้ที่เกี่ยวข้องเข้าร่วมตามวันและเวลาดังกล่าว', // Replied
    ];


    public const STATUS_SELECT = [
        'New'          => 'New',
        'Acknowledged' => 'Acknowledged',
        'Replied'      => 'Replied',
        'Other'        => 'Other',
        'Cancel'       => 'Cancel',
    ];


    protected $appends = [
        'letter_upload',
    ];

    protected $dates = [
        'sent_date',
        'received_date',
        'created_at',
        'start_date',
        'complete_date',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'letter_type',
        'title',
        'letter_no',
        'speed_class',
        'objective',
        'sender_id',
        'sent_date',
        'receiver_id',
        'received_date',
        'construction_contract_id',
        'created_at',
        'letter_iso_no',
        'create_by_id',
        'receive_by_id',
        'mask_as_received',
        'note',
        'responsible_id',
        'start_date',
        'complete_date',
        'processing_time',
        'updated_at',
        'deleted_at',
        'team_id',
        'status',
        'repiled_ref',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function topic_categories()
    {
        return $this->belongsToMany(LetterSubjectType::class);
    }

    public function sender()
    {
        return $this->belongsTo(Team::class, 'sender_id');
    }

    public function getSentDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setSentDateAttribute($value)
    {
        $this->attributes['sent_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function receiver()
    {
        return $this->belongsTo(Team::class, 'receiver_id');
    }

    public function getReceivedDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setReceivedDateAttribute($value)
    {
        $this->attributes['received_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function cc_tos()
    {
        return $this->belongsToMany(Team::class);
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getLetterUploadAttribute()
    {
        return $this->getMedia('letter_upload');
    }

    public function create_by()
    {
        return $this->belongsTo(User::class, 'create_by_id');
    }

    public function receive_by()
    {
        return $this->belongsTo(User::class, 'receive_by_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getCompleteDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setCompleteDateAttribute($value)
    {
        $this->attributes['complete_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
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
