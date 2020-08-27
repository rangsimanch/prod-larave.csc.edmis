<?php

namespace App\Http\Middleware;

use Closure;
use Gate;
use Illuminate\Support\Facades\Auth;



class ConstructionContractSelect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
       // If user isn't guest or doesn't have team id stored do relevant actions
       if (
            Auth::guest() ||
            session()->has('construction_contract_id') ||
            request()->routeIs('admin.construction_contracts-select.*') ||
            Gate::denies('construction_contract_select')
        ) 
        {   
            return $next($request);
            // return redirect()->back();
        } else 
        {
            // if user belongs only to one team store its id
            // if (Auth::user()->construction_contracts->count() === 1) 
            // {
            //     session()->put('construction_contract_id', Auth::user()->construction_contracts->first()->id);
            //     return $next($request);
            // }
           // return $next($request);
       
            return redirect()->route('admin.construction_contracts-select.select');
        }

    }
}
