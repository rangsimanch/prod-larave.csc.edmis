<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadSystemWork extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'download_system_works';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'work_title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
