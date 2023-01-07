<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmittalsRfa extends Model
{
    use SoftDeletes;

    public $table = 'submittals_rfas';

    protected $dates = [
        'date_returned',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'item_no',
        'description',
        'qty_sets',
        'review_status_id',
        'date_returned',
        'remarks',
        'on_rfa_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function review_status()
    {
        return $this->belongsTo(RfaCommentStatus::class, 'review_status_id');
    }

    public function getDateReturnedAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateReturnedAttribute($value)
    {
        $this->attributes['date_returned'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function on_rfa()
    {
        return $this->belongsTo(Rfa::class, 'on_rfa_id');
    }
}
