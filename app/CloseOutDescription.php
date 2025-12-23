<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CloseOutDescription extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'close_out_descriptions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'subject',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
