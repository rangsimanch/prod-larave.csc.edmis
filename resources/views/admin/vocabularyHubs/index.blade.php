@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.vocabularyHub.title') }}
                </div>
                <div class="panel-body">
                    <div class="container">
                        <a target="_blank" href="https://docs.google.com/spreadsheets/d/1V_QE65FabdViPAeKcy0pqEK0tjhfSF6upBUU-V-0o9I/edit?usp=sharing" class="btn btn-success btn-sm">
                             View on GoogleSheet 
                        </a>
                        <embed
                        src="https://docs.google.com/spreadsheets/d/e/2PACX-1vQaKMukuvIy_1Dy4qBc44bkLnMPdCgQsoLj7XDey03r2WRa9zWyvFAL9UDzCv5zwK3zIJ7bDhkO45gF/pubhtml"
                        height="640px"
                        width="100%"
                        >
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection