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

class RequestForInformation extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'request_for_informations';

    protected $appends = [
        'attachment_files',
    ];

    protected $dates = [
        'date',
        'incoming_date',
        'outgoing_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'to_organization',
        'attention_name',
        'document_no',
        'construction_contract_id',
        'date',
        'title',
        'to_id',
        'discipline',
        'originator_name',
        'cc_to',
        'incoming_no',
        'incoming_date',
        'description',
        'attachment_file_description',
        'request_by_id',
        'outgoing_no',
        'outgoing_date',
        'response',
        'authorised_rep_id',
        'response_organization_id',
        'response_date',
        'record',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function to()
    {
        return $this->belongsTo(Team::class, 'to_id');
    }

    public function getIncomingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setIncomingDateAttribute($value)
    {
        $this->attributes['incoming_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getAttachmentFilesAttribute()
    {
        return $this->getMedia('attachment_files');
    }

    public function request_by()
    {
        return $this->belongsTo(User::class, 'request_by_id');
    }

    public function getOutgoingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setOutgoingDateAttribute($value)
    {
        $this->attributes['outgoing_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function authorised_rep()
    {
        return $this->belongsTo(User::class, 'authorised_rep_id');
    }

    public function response_organization()
    {
        return $this->belongsTo(Team::class, 'response_organization_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
