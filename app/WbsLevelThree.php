<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WbsLevelThree extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'wbs_level_threes';

    public static $searchable = [
        'wbs_level_3_code',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'wbs_level_3_name',
        'wbs_level_3_code',
        'wbs_level_2_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function wbs_level_2()
    {
        return $this->belongsTo(BoQ::class, 'wbs_level_2_id');
    }
}
