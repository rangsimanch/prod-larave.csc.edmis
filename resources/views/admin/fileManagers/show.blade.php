@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.fileManager.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.file-managers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.fileManager.fields.id') }}
                        </th>
                        <td>
                            {{ $fileManager->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fileManager.fields.file_name') }}
                        </th>
                        <td>
                            {{ $fileManager->file_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fileManager.fields.code') }}
                        </th>
                        <td>
                            {{ $fileManager->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fileManager.fields.file_upload') }}
                        </th>
                        <td>
                            @foreach($fileManager->file_upload as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fileManager.fields.construction_contract') }}
                        </th>
                        <td>
                            {{ $fileManager->construction_contract->code ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.file-managers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection