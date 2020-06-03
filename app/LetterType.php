<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterType extends Model
{
    use SoftDeletes;

    public $table = 'letter_types';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'type_title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
