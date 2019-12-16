<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rfatype extends Model
{
    use SoftDeletes;

    public $table = 'rfatypes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'type_name',
        'type_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function rfas()
    {
        return $this->hasMany(Rfa::class, 'type_id', 'id');
    }
}
