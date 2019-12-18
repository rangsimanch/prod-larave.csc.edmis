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
        session()->has('construction_contracts_id') ||
        request()->routeIs('admin.constructionContracts-select.*') ||
        Gate::denies('constructionContracts_select')
        ) 
        {
        //      return redirect()->route('admin.constructionContracts-select.select');
        return $next($request);
        } 
    else {
        // if user belongs only to one team store its id
        if (Auth::User()->construction_contracts->count() === 1) {
            session()->put('construction_contracts_id', Auth::User()->construction_contracts->first()->id);
            return $next($request);
        }

        return redirect()->route('admin.constructionContracts-select.select');
        }
    }
}