<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class FileManager extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'file_managers';

    protected $appends = [
        'file_upload',
    ];

    public static $searchable = [
        'code',
        'file_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'team_id',
        'file_name',
        'created_at',
        'updated_at',
        'deleted_at',
        'construction_contract_id',
    ];


    /**
     * Set to null if empty
     * @param $input
     */
    public function setCreatedByConstructionContractIdAttribute($input)
    {
        $this->attributes['construction_contract_id'] = $input ? $input : null;
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getFileUploadAttribute()
    {
        return $this->getMedia('file_upload');
    }

    public function construction_contracts()
    {
        return $this->belongsToMany(ConstructionContract::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function create_by_construction_contract_id()
    {
        return $this->belongsToMany(ConstructionContract::class);
    }
}
