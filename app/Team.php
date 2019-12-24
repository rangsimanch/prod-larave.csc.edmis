<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    public $table = 'teams';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function teamUsers()
    {
        return $this->hasMany(User::class, 'team_id', 'id');
    }

    public function teamTasks()
    {
        return $this->hasMany(Task::class, 'team_id', 'id');
    }

    public function teamRfas()
    {
        return $this->hasMany(Rfa::class, 'team_id', 'id');
    }

    public function teamFileManagers()
    {
        return $this->hasMany(FileManager::class, 'team_id', 'id');
    }
}
