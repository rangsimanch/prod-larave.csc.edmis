<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Rfa;
use App\Complaint;
use DateTime;
use Illuminate\Support\Facades\Auth;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class HomeController
{
    public function index()
    {
        $date = new DateTime();
        $today = $date->format('Y-m-d');
        if(Auth::id() != 1){
        $chart = (new LarapexChart)->pieChart()
            ->setTitle('RFA')
            ->addData([
                \App\Rfa::where('construction_contract_id', '=', session('construction_contract_id'))
                    ->whereDate('submit_date', '=', $today)->count(),
                    \App\Rfa::where('document_status_id', '=', '1')
                    ->whereDate('submit_date', '=', $today)->count(),
                    \App\Rfa::where('document_status_id', '=', '2')
                    ->whereDate('submit_date', '=', $today)->count(),
                    \App\Rfa::where('document_status_id', '=', '3')
                    ->whereDate('submit_date', '=', $today)->count(),
                    \App\Rfa::where('document_status_id', '=', '4')
                    ->whereDate('submit_date', '=', $today)->count(),
                
                ])
            ->setColors(['#008FFB','#00E396','#feb019','#ff455f','#775dd0'])
            ->setXAxis(['Summary', 'New', 'Distribute', 'Reviwed', 'Done'])
            ->setGrid();
        }
        else{
            $chart = (new LarapexChart)->barChart()
            ->setTitle('RFA')
            ->addData('RFA',[
                \App\Rfa::whereDate('submit_date', '=', $today)->count(),
                \App\Rfa::where('document_status_id', '=', '1')
                    ->whereDate('submit_date', '=', $today)->count(),
                \App\Rfa::where('document_status_id', '=', '2')
                    ->whereDate('submit_date', '=', $today)->count(),
                \App\Rfa::where('document_status_id', '=', '3')
                    ->whereDate('submit_date', '=', $today)->count(),
                \App\Rfa::where('document_status_id', '=', '4')
                    ->whereDate('submit_date', '=', $today)->count(),
                ])
                ->setColors(['#008FFB','#00E396','#feb019','#ff455f','#775dd0'])
                ->setXAxis(['Summary', 'New', 'Distribute', 'Reviwed', 'Done'])
                ->setGrid();
        }
        return view('home', compact('chart','today'));
    }
}
