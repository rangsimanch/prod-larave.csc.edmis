<?php

// This is WBS Lv.5 (Detail of work)

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wbslevelfour extends Model
{
    use SoftDeletes;

    public $table = 'wbslevelfours';

    public static $searchable = [
        'wbs_level_4_code',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'boq_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'wbs_level_4_name',
        'wbs_level_4_code',
        'wbs_level_three_id',
    ];

    public function wbsLevel4Rfas()
    {
        return $this->hasMany(Rfa::class, 'wbs_level_4_id', 'id');
    }

    public function boq()
    {
        return $this->belongsTo(BoQ::class, 'boq_id');
    }

    public function wbs_level_three()
    {
        return $this->belongsTo(WbsLevelThree::class, 'wbs_level_three_id');
    }
}
