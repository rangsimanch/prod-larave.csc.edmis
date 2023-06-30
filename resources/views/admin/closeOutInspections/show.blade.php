@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.closeOutInspection.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-inspections.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.submit_date') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->submit_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.boq') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutInspection->boqs as $key => $boq)
                                            <span class="label label-info">{{ $boq->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.location') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutInspection->locations as $key => $location)
                                            <span class="label label-info">{{ $location->location_name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.sub_location') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->sub_location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.work_type') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutInspection->work_types as $key => $work_type)
                                            <span class="label label-info">{{ $work_type->work_type_name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.sub_worktype') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->sub_worktype }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.files_report_before') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutInspection->files_report_before as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.respond_date') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->respond_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.files_report_after') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutInspection->files_report_after as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.review_status') }}
                                    </th>
                                    <td>
                                        {{ App\CloseOutInspection::REVIEW_STATUS_SELECT[$closeOutInspection->review_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.review_date') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->review_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.reviewer') }}
                                    </th>
                                    <td>
                                        {{ $closeOutInspection->reviewer->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutInspection.fields.document_status') }}
                                    </th>
                                    <td>
                                        {{ App\CloseOutInspection::DOCUMENT_STATUS_SELECT[$closeOutInspection->document_status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-inspections.index') }}">
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