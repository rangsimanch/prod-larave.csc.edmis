<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WbsLevelThree extends Model
{
    use SoftDeletes;

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
        'created_at',
        'updated_at',
        'deleted_at',
        'wbs_level_3_name',
        'wbs_level_3_code',
    ];

    public function wbsLevel3Rfas()
    {
        return $this->hasMany(Rfa::class, 'wbs_level_3_id', 'id');
    }
}
