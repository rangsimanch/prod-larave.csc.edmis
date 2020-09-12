@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.srtHeadOfficeDocument.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-head-office-documents.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $srtHeadOfficeDocument->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.refer_documents') }}
                                    </th>
                                    <td>
                                        {{ $srtHeadOfficeDocument->refer_documents->document_number ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.process_date') }}
                                    </th>
                                    <td>
                                        {{ $srtHeadOfficeDocument->process_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.special_command') }}
                                    </th>
                                    <td>
                                        {{ $srtHeadOfficeDocument->special_command->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.finished_date') }}
                                    </th>
                                    <td>
                                        {{ $srtHeadOfficeDocument->finished_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.practitioner') }}
                                    </th>
                                    <td>
                                        {{ $srtHeadOfficeDocument->practitioner }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.practice_notes') }}
                                    </th>
                                    <td>
                                        {{ $srtHeadOfficeDocument->practice_notes }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $srtHeadOfficeDocument->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtHeadOfficeDocument.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($srtHeadOfficeDocument->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-head-office-documents.index') }}">
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