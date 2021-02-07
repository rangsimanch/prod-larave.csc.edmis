<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoQ extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'bo_qs';

    public static $searchable = [
        'name',
    ];

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
        return $this->belongsTo(WbsLevelOne::class, 'wbs_lv_1_id');
    }
}
