<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Rfa;
use App\Complaint;
use DateTime;
use Illuminate\Support\Facades\Auth;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Announcement;


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
                
                ])
            ->setColors(['#ffc63b'])
            ->setLabels(['RFA Count']
            );
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

        $activeAnnounce_description =  Announcement::where('start_date', '<=', $today)->where('end_date', '>=', $today)->pluck('description');
        $activeAnnounce_title = Announcement::where('start_date', '<=', $today)->where('end_date', '>=', $today)->pluck('title');
        $announce = "";
        for($x = 0; $x < sizeof($activeAnnounce_description); $x++){
            $announce .= $activeAnnounce_description[$x];
            if($x != sizeof($activeAnnounce_description)-1){
                $announce .= " | ";
            }
        }

        $announce = str_replace("\r","",$announce);

        return view('home', compact('chart','today','announce'));
    }
}
