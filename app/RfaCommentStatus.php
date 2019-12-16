<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RfaCommentStatus extends Model
{
    use SoftDeletes;

    public $table = 'rfa_comment_statuses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function rfas()
    {
        return $this->hasMany(Rfa::class, 'comment_status_id', 'id');
    }
}
