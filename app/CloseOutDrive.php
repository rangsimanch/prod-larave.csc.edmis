<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CloseOutDrive extends Model
{
    use SoftDeletes, MultiTenantModelTrait, Auditable;

    public $table = 'close_out_drives';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'filename',
        'url',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function closeoutUrlCloseOutMains()
    {
        return $this->belongsToMany(CloseOutMain::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
