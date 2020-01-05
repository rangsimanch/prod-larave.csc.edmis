<?php

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
        'created_at',
        'updated_at',
        'deleted_at',
        'wbs_level_4_name',
        'wbs_level_4_code',
    ];

    public function wbsLevel4Rfas()
    {
        return $this->hasMany(Rfa::class, 'wbs_level_4_id', 'id');
    }
}
