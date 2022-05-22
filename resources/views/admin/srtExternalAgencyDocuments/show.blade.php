@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.srtExternalAgencyDocument.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-external-agency-documents.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        @foreach($srtExternalAgencyDocument->construction_contracts as $key => $construction_contract)
                                            <span class="label label-info">{{ $construction_contract->code }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.document_type') }}
                                    </th>
                                    <td>
                                        {{ App\SrtExternalAgencyDocument::DOCUMENT_TYPE_SELECT[$srtExternalAgencyDocument->document_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.originator_number') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->originator_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.incoming_date') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->incoming_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.refer_to') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->refer_to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.from') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->from }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.to') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.attachments') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->attachments }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.speed_class') }}
                                    </th>
                                    <td>
                                        {{ App\SrtExternalAgencyDocument::SPEED_CLASS_SELECT[$srtExternalAgencyDocument->speed_class] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.objective') }}
                                    </th>
                                    <td>
                                        {{ App\SrtExternalAgencyDocument::OBJECTIVE_SELECT[$srtExternalAgencyDocument->objective] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.signatory') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->signatory }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.document_storage') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->document_storage }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.close_date') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalAgencyDocument->close_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($srtExternalAgencyDocument->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalAgencyDocument.fields.save_for') }}
                                    </th>
                                    <td>
                                        {{ App\SrtExternalAgencyDocument::SAVE_FOR_SELECT[$srtExternalAgencyDocument->save_for] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-external-agency-documents.index') }}">
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