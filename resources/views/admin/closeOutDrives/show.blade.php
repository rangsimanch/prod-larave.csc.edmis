@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.closeOutDrive.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-drives.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutDrive.fields.filename') }}
                                    </th>
                                    <td>
                                        {{ $closeOutDrive->filename }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutDrive.fields.url') }}
                                    </th>
                                    <td>
                                        {{ $closeOutDrive->url }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-drives.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.relatedData') }}
                </div>
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    <li role="presentation">
                        <a href="#closeout_url_close_out_mains" aria-controls="closeout_url_close_out_mains" role="tab" data-toggle="tab">
                            {{ trans('cruds.closeOutMain.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="closeout_url_close_out_mains">
                        @includeIf('admin.closeOutDrives.relationships.closeoutUrlCloseOutMains', ['closeOutMains' => $closeOutDrive->closeoutUrlCloseOutMains])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection