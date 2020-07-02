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
        'name',
        'code',
        'amount',
        'boq_id',
        'created_at',
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
}
