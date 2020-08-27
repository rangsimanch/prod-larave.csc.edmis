<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoqItem extends Model
{
    use SoftDeletes, MultiTenantModelTrait, Auditable;

    public $table = 'boq_items';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'boq_id',
        'code',
        'name',
        'created_at',
        'unit',
        'quantity',
        'unit_rate',
        'amount',
        'factor_f',
        'unit_rate_x_ff',
        'total_amount',
        'remark',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function boq()
    {
        return $this->belongsTo(BoQ::class, 'boq_id');
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
