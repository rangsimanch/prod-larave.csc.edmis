<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoqItem extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'boq_items';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'boq_id',
        'code',
        'name',
        'created_at',
        'unit',
        'quantity',
        'unit_rate',
        'amount',
        'factor_f',
        'unit_rate_x_ff',
        'total_amount',
        'remark',
        'updated_at',
        'deleted_at',
    ];

    public function boq()
    {
        return $this->belongsTo(BoQ::class, 'boq_id');
    }
}
