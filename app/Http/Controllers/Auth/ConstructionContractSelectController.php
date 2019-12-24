<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ConstructionContractSelectController extends Controller
{

    function __construct()
    {
     $this->middleware('web');
    }
    
    public function select()
    {
        $construction_contracts = auth()->user()->construction_contracts->pluck('code' , 'id');

        return view('auth.selectConstructionContract', compact('construction_contracts'));
    }

    public function storeSelect(Request $request)
    {
        if (!$request->has('construction_contract_id')) {
            return back();
        }

        session()->put('construction_contract_id', $request->input('construction_contract_id'));

        if ($request->input('redirect') === 'back') {
            return back();
        }

        return redirect()->to('/');

        //return back();
    }
}
