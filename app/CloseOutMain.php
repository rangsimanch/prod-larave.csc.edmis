<?php

namespace App;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class CloseOutMain extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, HasMediaTrait, Auditable;

    public $table = 'close_out_mains';

    protected $appends = [
        'final_file',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'in_progress' => 'In Progress',
        'done'        => 'Done',
    ];

    protected $fillable = [
        'status',
        'construction_contract_id',
        'closeout_subject_id',
        'detail',
        'description',
        'quantity',
        'ref_documents',
        'ref_rfa_text',
        'remark',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function construction_contract()
    {
        return $this->belongsTo(ConstructionContract::class, 'construction_contract_id');
    }

    public function closeout_subject()
    {
        return $this->belongsTo(CloseOutDescription::class, 'closeout_subject_id');
    }

    public function getFinalFileAttribute()
    {
        return $this->getMedia('final_file');
    }

    public function closeout_urls()
    {
        return $this->belongsToMany(CloseOutDrive::class);
    }

    public function ref_rfas()
    {
        return $this->belongsToMany(Rfa::class);
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
