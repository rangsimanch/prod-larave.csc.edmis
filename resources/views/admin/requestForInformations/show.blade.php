@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.requestForInformation.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.request-for-informations.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.to_organization') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->to_organization }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.attention_name') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->attention_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.document_no') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->document_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.date') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.to') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->to->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.discipline') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->discipline }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.originator_name') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->originator_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.cc_to') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->cc_to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.incoming_no') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->incoming_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.incoming_date') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->incoming_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.attachment_file_description') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->attachment_file_description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.attachment_files') }}
                                    </th>
                                    <td>
                                        @foreach($requestForInformation->attachment_files as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.request_by') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->request_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.outgoing_no') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->outgoing_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.outgoing_date') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->outgoing_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.response') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->response }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.authorised_rep') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->authorised_rep->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.response_organization') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->response_organization->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.response_date') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->response_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInformation.fields.record') }}
                                    </th>
                                    <td>
                                        {{ $requestForInformation->record }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.request-for-informations.index') }}">
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