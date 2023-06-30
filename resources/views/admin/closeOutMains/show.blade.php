@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.closeOutMain.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-mains.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.chapter_no') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->chapter_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.final_file') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutMain->final_file as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-mains.index') }}">
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