<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController 
{
    public function index()
    {
        $settings1 = [
            'chart_title'           => 'Complaint Chart',
            'chart_type'            => 'bar',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Complaint',
            'group_by_field'        => 'received_date',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_period'         => 'month',
            'group_by_field_format' => 'd/m/Y',
            'column_class'          => 'col-md-12',
            'entries_number'        => '5',
        ];

        $chart1 = new LaravelChart($settings1);


        return view('home', compact('test'));
    }
}
