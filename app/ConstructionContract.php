<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionContract extends Model
{
    use SoftDeletes;

    public $table = 'construction_contracts';

    public static $searchable = [
        'name',
        'code',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'code',
        'budget',
        'dk_end_1',
        'dk_end_2',
        'dk_end_3',
        'dk_start_1',
        'dk_start_2',
        'dk_start_3',
        'roadway_km',
        'tollway_km',
        'created_at',
        'updated_at',
        'deleted_at',
        'total_distance_km',
    ];

    public function constructionContractRfas()
    {
        return $this->belongsToMany(Rfa::class);
    }

    public function constructionContractUsers()
    {
        return $this->belongsToMany(User::class);
    }

    public function constructionContractTasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function constructionContractFileManagers()
    {
        return $this->belongsToMany(FileManager::class);
    }
}
