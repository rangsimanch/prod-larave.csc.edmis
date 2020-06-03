<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadSystemActivity extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'download_system_activities';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'activity_title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
