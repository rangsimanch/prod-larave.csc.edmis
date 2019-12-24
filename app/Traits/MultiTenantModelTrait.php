<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;

use App\User;
=======
>>>>>>> parent of 2c893c1... Constraction Contract Select
=======
>>>>>>> parent of 2c893c1... Constraction Contract Select

trait MultiTenantModelTrait
{
    public static function bootMultiTenantModelTrait()
    {
        if (!app()->runningInConsole() && auth()->check()) {
            $isAdmin = auth()->user()->roles->contains(1);
            
            static::creating(function ($model) use ($isAdmin) {
// Prevent admin from setting his own id - admin entries are global.

// If required, remove the surrounding IF condition and admins will act as users
                if (!$isAdmin) {
                    $model->team_id = auth()->user()->team_id;
<<<<<<< HEAD
<<<<<<< HEAD
                    $model->construction_contract_id = session('construction_contract_id');
=======
>>>>>>> parent of 2c893c1... Constraction Contract Select
=======
>>>>>>> parent of 2c893c1... Constraction Contract Select
                }
            });
            if (!$isAdmin) {
                static::addGlobalScope('team_id', function (Builder $builder) {
                    $field = sprintf('%s.%s', $builder->getQuery()->from, 'team_id');
                    $builder->where($field, auth()->user()->team_id)->orWhereNull($field);
                });
<<<<<<< HEAD
<<<<<<< HEAD
                
=======
>>>>>>> parent of 2c893c1... Constraction Contract Select
=======
>>>>>>> parent of 2c893c1... Constraction Contract Select
            }
        }
    }
}
