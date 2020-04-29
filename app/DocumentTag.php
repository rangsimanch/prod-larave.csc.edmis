<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTag extends Model
{
    use SoftDeletes;

    public $table = 'document_tags';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'tag_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
