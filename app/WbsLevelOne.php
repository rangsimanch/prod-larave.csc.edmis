<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WbsLevelOne extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'wbs_level_ones';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'code',
        'wbs_lv_1_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function wbs_lv_1()
    {
        return $this->belongsTo(WbsLevelFive::class, 'wbs_lv_1_id');
    }
}
