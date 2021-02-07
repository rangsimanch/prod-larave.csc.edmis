@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.complaint.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.complaints.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $complaint->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Complaint::STATUS_SELECT[$complaint->status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $complaint->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $complaint->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.complaint_recipient') }}
                                    </th>
                                    <td>
                                        {{ $complaint->complaint_recipient->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.received_date') }}
                                    </th>
                                    <td>
                                        {{ $complaint->received_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.source_code') }}
                                    </th>
                                    <td>
                                        {{ App\Complaint::SOURCE_CODE_SELECT[$complaint->source_code] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.file_attachment_create') }}
                                    </th>
                                    <td>
                                        @foreach($complaint->file_attachment_create as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.complainant') }}
                                    </th>
                                    <td>
                                        {{ $complaint->complainant }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.complainant_tel') }}
                                    </th>
                                    <td>
                                        {{ $complaint->complainant_tel }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.complainant_detail') }}
                                    </th>
                                    <td>
                                        {{ $complaint->complainant_detail }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.complaint_description') }}
                                    </th>
                                    <td>
                                        {{ $complaint->complaint_description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.type_code') }}
                                    </th>
                                    <td>
                                        {{ App\Complaint::TYPE_CODE_SELECT[$complaint->type_code] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.impact_code') }}
                                    </th>
                                    <td>
                                        {{ App\Complaint::IMPACT_CODE_SELECT[$complaint->impact_code] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.operator') }}
                                    </th>
                                    <td>
                                        {{ $complaint->operator->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.action_detail') }}
                                    </th>
                                    <td>
                                        {{ $complaint->action_detail }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.progress_file') }}
                                    </th>
                                    <td>
                                        @foreach($complaint->progress_file as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.complaint.fields.action_date') }}
                                    </th>
                                    <td>
                                        {{ $complaint->action_date }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.complaints.index') }}">
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