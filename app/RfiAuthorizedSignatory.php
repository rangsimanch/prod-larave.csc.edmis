<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RfiAuthorizedSignatory extends Model
{
    public $table = 'rfi_authorized_signatory';

    protected $fillable = [
        'request_for_information_id',
        'authorized_signatory_id',
        'authorized_signatory_jobtitle_id',
        'slot',
    ];

    public function rfi()
    {
        return $this->belongsTo(RequestForInformation::class, 'request_for_information_id');
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
