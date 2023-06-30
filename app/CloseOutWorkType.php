<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CloseOutWorkType extends Model
{
    use SoftDeletes, MultiTenantModelTrait, Auditable;

    public $table = 'close_out_work_types';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'work_type_name',
        'work_type_code',
        'construction_contract_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function create_by_construction_contract_id()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }
}
