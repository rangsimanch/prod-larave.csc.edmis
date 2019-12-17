@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.rfa.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rfas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.title') }}
                        </th>
                        <td>
                            {{ $rfa->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.document_number') }}
                        </th>
                        <td>
                            {{ $rfa->document_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.rfa_code') }}
                        </th>
                        <td>
                            {{ $rfa->rfa_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.type') }}
                        </th>
                        <td>
                            {{ $rfa->type->type_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.submit_date') }}
                        </th>
                        <td>
                            {{ $rfa->submit_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.issueby') }}
                        </th>
                        <td>
                            {{ $rfa->issueby->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.assign') }}
                        </th>
                        <td>
                            {{ $rfa->assign->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.note_1') }}
                        </th>
                        <td>
                            {!! $rfa->note_1 !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.file_upload_1') }}
                        </th>
                        <td>
                            @foreach($rfa->file_upload_1 as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.create_by') }}
                        </th>
                        <td>
                            {{ $rfa->create_by->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.action_by') }}
                        </th>
                        <td>
                            {{ $rfa->action_by->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.comment_by') }}
                        </th>
                        <td>
                            {{ $rfa->comment_by->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.information_by') }}
                        </th>
                        <td>
                            {{ $rfa->information_by->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.receive_date') }}
                        </th>
                        <td>
                            {{ $rfa->receive_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.note_2') }}
                        </th>
                        <td>
                            {!! $rfa->note_2 !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.comment_status') }}
                        </th>
                        <td>
                            {{ $rfa->comment_status->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.note_3') }}
                        </th>
                        <td>
                            {{ $rfa->note_3 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.for_status') }}
                        </th>
                        <td>
                            {{ $rfa->for_status->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.document_status') }}
                        </th>
                        <td>
                            {{ $rfa->document_status->status_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfa.fields.construction_contract') }}
                        </th>
                        <td>
                            @foreach($rfa->construction_contracts as $key => $construction_contract)
                                <span class="label label-info">{{ $construction_contract->code }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rfas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection