<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class DocumentsAndAssignment extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, Auditable;

    protected $appends = [
        'file_upload',
    ];

    public $table = 'documents_and_assignments';

    protected $dates = [
        'date_of_receipt',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'file_name',
        'original_no',
        'receipt_no',
        'date_of_receipt',
        'received_from',
        'construction_contract_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getDateOfReceiptAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfReceiptAttribute($value)
    {
        $this->attributes['date_of_receipt'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
    }

    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }
}
