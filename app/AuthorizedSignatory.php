<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizedSignatory extends Model
{
    use SoftDeletes;

    public $table = 'authorized_signatories';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'name_rfi',
        'signature_path',
        'signature_size_h',
        'signature_size_w',
        'is_default',
        'is_active',
        'sort_order',
        'construction_contract_id',
    ];

    public function constructionContract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function jobtitles()
    {
        return $this->hasMany(AuthorizedSignatoryJobtitle::class, 'authorized_signatory_id', 'id')->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
