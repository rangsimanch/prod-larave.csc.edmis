@extends('layouts.admin')
@section('content') 
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.dailyReport.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.daily-reports.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.input_date') }}
                                    </th>
                                    <td>
                                        {{ $dailyReport->input_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.documents') }}
                                    </th>
                                    <td>
                                        @foreach($dailyReport->documents as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.document_code') }}
                                    </th>
                                    <td>
                                        {{ $dailyReport->document_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyRequest.fields.receive_by') }}
                                    </th>
                                    <td>
                                        {{ $dailyRequest->receive_by->name ?? '' }}
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.acknowledge_date') }}
                                    </th>
                                    <td>
                                        {{ $dailyReport->acknowledge_date }}
                                    </td>
                                </tr> -->
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $dailyReport->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.daily-reports.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection