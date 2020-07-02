@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.nonConformanceReport.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.non-conformance-reports.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.ncn_ref') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->ncn_ref->ref_no ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.corresponding_to') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->corresponding_to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.response_date') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->response_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.root_cause') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->root_cause }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.corrective_action') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->corrective_action }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.preventive_action') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->preventive_action }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.ref_no') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->ref_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.attachment') }}
                                    </th>
                                    <td>
                                        @foreach($nonConformanceReport->attachment as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.prepared_by') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->prepared_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.contractors_project') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->contractors_project->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.csc_consideration_status') }}
                                    </th>
                                    <td>
                                        {{ App\NonConformanceReport::CSC_CONSIDERATION_STATUS_SELECT[$nonConformanceReport->csc_consideration_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.approved_by') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->approved_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.csc_disposition_status') }}
                                    </th>
                                    <td>
                                        {{ App\NonConformanceReport::CSC_DISPOSITION_STATUS_SELECT[$nonConformanceReport->csc_disposition_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceReport->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceReport.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($nonConformanceReport->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.non-conformance-reports.index') }}">
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