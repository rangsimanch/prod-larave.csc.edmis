@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.srtPdDocument.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-pd-documents.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPdDocument.fields.refer_documents') }}
                                    </th>
                                    <td>
                                        {{ $srtPdDocument->refer_documents->document_number ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPdDocument.fields.process_date') }}
                                    </th>
                                    <td>
                                        {{ $srtPdDocument->process_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPdDocument.fields.special_command') }}
                                    </th>
                                    <td>
                                        {{ App\SrtPdDocument::SPECIAL_COMMAND_SELECT[$srtPdDocument->special_command] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPdDocument.fields.finished_date') }}
                                    </th>
                                    <td>
                                        {{ $srtPdDocument->finished_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPdDocument.fields.operator') }}
                                    </th>
                                    <td>
                                        @foreach($srtPdDocument->operators as $key => $operator)
                                            <span class="label label-info">{{ $operator->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPdDocument.fields.practice_notes') }}
                                    </th>
                                    <td>
                                        {{ $srtPdDocument->practice_notes }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPdDocument.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $srtPdDocument->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPdDocument.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($srtPdDocument->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-pd-documents.index') }}">
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