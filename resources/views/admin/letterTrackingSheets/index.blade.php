@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.letterTrackingSheet.title') }}
                </div>
                <div class="panel-body">
                    <div class="panel-body">
                    <div class="container">
                        <a target="_blank" href="https://docs.google.com/spreadsheets/d/1gUXIvmyEkHk-vNCbx81_8REKPVcbvM6ICZ2bQ8pI5ro/edit?usp=sharing" class="btn btn-success btn-sm">
                             View on GoogleSheet 
                        </a>
                        <embed
                        src="https://docs.google.com/spreadsheets/d/e/2PACX-1vR9qpKWO7h_xLHiqOdx7_CfTphQ-x4JqlyKLmgGXiYt-UwTxGw9GTWEmS41DdvkP06EjI4ukU2-tp0N/pubhtml"
                        height="640px"
                        width="100%"
                        >
                    </div>
                </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection