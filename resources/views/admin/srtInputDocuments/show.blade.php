@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.srtInputDocument.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-input-documents.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.document_type') }}
                                    </th>
                                    <td>
                                        {{ App\SrtInputDocument::DOCUMENT_TYPE_SELECT[$srtInputDocument->document_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.incoming_date') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->incoming_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.refer_to') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->refer_to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.attachments') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->attachments }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.from') }}
                                    </th>
                                    <td>
                                        {{ App\SrtInputDocument::FROM_SELECT[$srtInputDocument->from] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.to') }}
                                    </th>
                                    <td>
                                        {{ App\SrtInputDocument::TO_SELECT[$srtInputDocument->to] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.speed_class') }}
                                    </th>
                                    <td>
                                        {{ App\SrtInputDocument::SPEED_CLASS_SELECT[$srtInputDocument->speed_class] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.objective') }}
                                    </th>
                                    <td>
                                        {{ App\SrtInputDocument::OBJECTIVE_SELECT[$srtInputDocument->objective] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.signer') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->signer }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.document_storage') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->document_storage }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.docuement_status') }}
                                    </th>
                                    <td>
                                        {{ $srtInputDocument->docuement_status->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtInputDocument.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($srtInputDocument->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-input-documents.index') }}">
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