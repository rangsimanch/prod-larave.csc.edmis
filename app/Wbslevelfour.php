<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wbslevelfour extends Model
{
    use SoftDeletes, Auditable;

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
        'wbs_level_4_name',
        'wbs_level_4_code',
        'wbs_level_three_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function wbs_level_three()
    {
        return $this->belongsTo(WbsLevelThree::class, 'wbs_level_three_id');
    }
}
