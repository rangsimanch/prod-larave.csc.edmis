@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.srtExternalDocument.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-external-documents.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.docuement_status') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->docuement_status->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.constuction_contract') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->constuction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.document_type') }}
                                    </th>
                                    <td>
                                        {{ App\SrtExternalDocument::DOCUMENT_TYPE_SELECT[$srtExternalDocument->document_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.incoming_date') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->incoming_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.refer_to') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->refer_to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.from') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->from->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.to') }}
                                    </th>
                                    <td>
                                        @foreach($srtExternalDocument->tos as $key => $to)
                                            <span class="label label-info">{{ $to->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.attachments') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->attachments }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.speed_class') }}
                                    </th>
                                    <td>
                                        {{ App\SrtExternalDocument::SPEED_CLASS_SELECT[$srtExternalDocument->speed_class] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.objective') }}
                                    </th>
                                    <td>
                                        {{ App\SrtExternalDocument::OBJECTIVE_SELECT[$srtExternalDocument->objective] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.signatory') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->signatory }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.document_storage') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->document_storage }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.close_date') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->close_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($srtExternalDocument->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.save_for') }}
                                    </th>
                                    <td>
                                        {{ App\SrtExternalDocument::SAVE_FOR_SELECT[$srtExternalDocument->save_for] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtExternalDocument.fields.close_by') }}
                                    </th>
                                    <td>
                                        {{ $srtExternalDocument->close_by->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-external-documents.index') }}">
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