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
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'add_letters';

    protected $appends = [
        'letter_upload',
    ];

    protected $dates = [
        'sent_date',
        'received_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'letter_type_id',
        'letter_no',
        'sender_id',
        'sent_date',
        'receiver_id',
        'received_date',
        'cc_srt',
        'cc_pmc',
        'cc_csc',
        'cc_cec',
        'construction_contract_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function letter_type()
    {
        return $this->belongsTo(LetterType::class, 'letter_type_id');
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

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getLetterUploadAttribute()
    {
        return $this->getMedia('letter_upload');
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
