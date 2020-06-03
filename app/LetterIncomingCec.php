<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterIncomingCec extends Model
{
    use SoftDeletes, MultiTenantModelTrait, Auditable;

    public $table = 'letter_incoming_cecs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'letter_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function letter()
    {
        return $this->belongsTo(AddLetter::class, 'letter_id');
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
