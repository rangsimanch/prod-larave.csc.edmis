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
       
        $announce_text = "";
        $blank_space = "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
        $modelAnnouncements = Announcement::where('start_date', '<=', $today)->where('end_date', '>=', $today)->get(); 
        foreach($modelAnnouncements as $activeAnnouncement){
            if(count($activeAnnouncement->attachments) > 0)
                $announce_text .= '<a href="' . $activeAnnouncement->attachments[0]->getUrl() . '" target="_blank">'  . $activeAnnouncement->description . "</a>" . $blank_space;
            else
                $announce_text .= $activeAnnouncement->description . $blank_space;
        }
        
        return view('home', compact('chart','today','announce_text'));
    }

}
