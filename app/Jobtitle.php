<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jobtitle extends Model
{
    use SoftDeletes;

    public $table = 'jobtitles';

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

    public function users()
    {
        return $this->hasMany(User::class, 'jobtitle_id', 'id');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}
