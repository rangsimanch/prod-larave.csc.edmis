<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RfaDocumentStatus extends Model
{
    use SoftDeletes;

    public $table = 'rfa_document_statuses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'updated_at',
        'created_at',
        'deleted_at',
        'status_name',
    ];

    public function rfas()
    {
        return $this->hasMany(Rfa::class, 'document_status_id', 'id');
    }
}
