@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.srtPeDocument.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-pe-documents.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPeDocument.fields.refer_documents') }}
                                    </th>
                                    <td>
                                        {{ $srtPeDocument->refer_documents->document_number ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPeDocument.fields.process_date') }}
                                    </th>
                                    <td>
                                        {{ $srtPeDocument->process_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPeDocument.fields.special_command') }}
                                    </th>
                                    <td>
                                        {{ App\SrtPeDocument::SPECIAL_COMMAND_SELECT[$srtPeDocument->special_command] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPeDocument.fields.finished_date') }}
                                    </th>
                                    <td>
                                        {{ $srtPeDocument->finished_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPeDocument.fields.operator') }}
                                    </th>
                                    <td>
                                        @foreach($srtPeDocument->operators as $key => $operator)
                                            <span class="label label-info">{{ $operator->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPeDocument.fields.practice_notes') }}
                                    </th>
                                    <td>
                                        {{ $srtPeDocument->practice_notes }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPeDocument.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $srtPeDocument->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtPeDocument.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($srtPeDocument->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-pe-documents.index') }}">
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