<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait MultiTenantModelTrait
{
    public static function bootMultiTenantModelTrait()
    {
        if (!app()->runningInConsole() && auth()->check()) {
            $isAdmin = auth()->user()->roles->contains(1);

            // **Roles ID Dev
            // $isTeamSRT = auth()->user()->roles->contains(4);
            // $isTeamPMC = auth()->user()->roles->contains(5);
            // $isTeamCSC = auth()->user()->roles->contains(6);
            // $isTeamCEC = auth()->user()->roles->contains(7);

            // **Roles ID Prod
            // $isTeamSRT = auth()->user()->roles->contains(15);
            // $isTeamPMC = auth()->user()->roles->contains(16);
            // $isTeamCSC = auth()->user()->roles->contains(17);
            // $isTeamCEC = auth()->user()->roles->contains(18);

            // static::creating(function ($model) use ($isAdmin, $isTeamSRT, $isTeamPMC,$isTeamCSC,$isTeamCEC) {
            static::creating(function ($model) use ($isAdmin) {

        // **Prevent admin from setting his own id - admin entries are global.
        // **If required, remove the surrounding IF condition and admins will act as users
                if (!$isAdmin) {
                    // **Check Team Status
                        // $model->team_id = auth()->user()->team_id;

                    // **Contract Value
                        $model->construction_contract_id = session('construction_contract_id');   
                }
            });

        // *** Team Function

                // if (!$isAdmin && !$isTeamSRT) {
                if (!$isAdmin) {


                    // if($isTeamPMC){
                    //     static::addGlobalScope('team_id', function (Builder $builder) {
                    //         //Team ID
                    //         $field_pmc = "2,3,4";
                    //         $field = sprintf('%s.%s', $builder->getQuery()->from, 'team_id');
                    //         $builder->whereIn($field, explode(',',$field_pmc))
                    //         ->orWhereNull($field);
                    //     });
                    // }
                    // else{
                    //     static::addGlobalScope('team_id', function (Builder $builder) {
                    //         //Team ID
                    //         $field_csc = "3,4";
                    //         $field = sprintf('%s.%s', $builder->getQuery()->from, 'team_id');
                    //         $builder->whereIn($field, explode(',',$field_csc))
                    //         ->orWhereNull($field);
                    //     });
                    // }

                    $currentUser = Auth::user();
                    if(!$currentUser){
                        return;
                    }

                    // **Contract Check
                    if(session()->has('construction_contract_id')){
                        if(((new self)->getTable()) == 'construction_contracts'){
                            static::addGlobalScope('construction_contract_id', function (Builder $builder) use($currentUser){
                                $builder->whereHas('create_by_construction_contract_id', function($q){
                                    $q->where('id', session('construction_contract_id', null));
                                })->orWhereIn('id', $currentUser->construction_contracts->pluck('id'), null);
                            });
                        }else{
                            static::addGlobalScope('construction_contract_id', function (Builder $builder) use($currentUser){
                                $builder->whereHas('create_by_construction_contract_id', function($q){
                                    $q->where('construction_contract_id', session('construction_contract_id', null));
                                });
                            });
                        }
                    }
                }     
        }
    }
}
