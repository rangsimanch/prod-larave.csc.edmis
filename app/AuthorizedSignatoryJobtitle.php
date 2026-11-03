<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorizedSignatoryJobtitle extends Model
{
    public $table = 'authorized_signatory_jobtitles';

    protected $fillable = [
        'jobtitle',
        'signature_position_top',
        'signature_position_left',
        'jobtitle_position_lf',
        'issue_position_lf',
        'issue_position_lf_sub',
        'is_default',
        'is_active',
        'sort_order',
        'authorized_signatory_id',
    ];

    public function signatory()
    {
        return $this->belongsTo(AuthorizedSignatory::class, 'authorized_signatory_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
