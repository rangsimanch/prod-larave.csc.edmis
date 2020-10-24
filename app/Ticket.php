<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Ticket extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, Auditable;

    public $table = 'tickets';

    protected $appends = [
        'attachment',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const STATUS_SELECT = [
        'Requested' => 'Requested',
        'Solved'    => 'Solved',
    ];

    protected $fillable = [
        'subject',
        'ticket_code',
        'request_type',
        'module',
        'detail',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const REQUEST_TYPE_SELECT = [
        'GN' => 'General and Feedback',
        'TN' => 'Technical Issue : Page error, Display error, etc.',
        'AC' => 'Account Management, Data Requests or Deletion',
    ];

    const MODULE_SELECT = [
        'RFA' => 'RFA',
        'RFN' => 'RFN',
        'RFI' => 'RFI',
        'SWN' => 'SWN',
        'NCN' => 'NCN',
        'NCR' => 'NCR',
        'SRT' => 'SRT Documents',
        'CAR' => 'CSC Activity Report',
        'DCA' => 'Daily Construction Activity',
        'DRQ' => 'CCSP Daily Request',
        'DRP' => 'CCSP Daily Report',
        'LET' => 'Letter',
        'DLS' => 'Download Systems',
        'ETC' => 'Etc.',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment');
    }
}
