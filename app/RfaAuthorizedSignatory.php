<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RfaAuthorizedSignatory extends Model
{
    public $table = 'rfa_authorized_signatory';

    protected $fillable = [
        'rfa_id',
        'authorized_signatory_id',
        'authorized_signatory_jobtitle_id',
        'slot',
    ];

    public function rfa()
    {
        return $this->belongsTo(Rfa::class, 'rfa_id');
    }

    public function signatory()
    {
        return $this->belongsTo(AuthorizedSignatory::class, 'authorized_signatory_id');
    }

    public function jobtitle()
    {
        return $this->belongsTo(AuthorizedSignatoryJobtitle::class, 'authorized_signatory_jobtitle_id');
    }
}
