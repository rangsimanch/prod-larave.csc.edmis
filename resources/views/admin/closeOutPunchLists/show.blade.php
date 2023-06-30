@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.closeOutPunchList.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-punch-lists.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.submit_date') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->submit_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.boq') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutPunchList->boqs as $key => $boq)
                                            <span class="label label-info">{{ $boq->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.location') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutPunchList->locations as $key => $location)
                                            <span class="label label-info">{{ $location->location_name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.sub_location') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->sub_location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.work_type') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutPunchList->work_types as $key => $work_type)
                                            <span class="label label-info">{{ $work_type->work_type_name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.sub_worktype') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->sub_worktype }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.files_report_before') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutPunchList->files_report_before as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.respond_date') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->respond_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.files_report_after') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutPunchList->files_report_after as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.review_status') }}
                                    </th>
                                    <td>
                                        {{ App\CloseOutPunchList::REVIEW_STATUS_SELECT[$closeOutPunchList->review_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.review_date') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->review_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.reviewer') }}
                                    </th>
                                    <td>
                                        {{ $closeOutPunchList->reviewer->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutPunchList.fields.document_status') }}
                                    </th>
                                    <td>
                                        {{ App\CloseOutPunchList::DOCUMENT_STATUS_SELECT[$closeOutPunchList->document_status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-punch-lists.index') }}">
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