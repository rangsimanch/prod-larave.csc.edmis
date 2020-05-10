@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.droneVdoRecorded.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.drone-vdo-recordeds.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.droneVdoRecorded.fields.work_tiltle') }}
                                    </th>
                                    <td>
                                        {{ $droneVdoRecorded->work_tiltle }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.droneVdoRecorded.fields.details') }}
                                    </th>
                                    <td>
                                        {{ $droneVdoRecorded->details }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.droneVdoRecorded.fields.operation_date') }}
                                    </th>
                                    <td>
                                        {{ $droneVdoRecorded->operation_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.droneVdoRecorded.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $droneVdoRecorded->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.droneVdoRecorded.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($droneVdoRecorded->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.drone-vdo-recordeds.index') }}">
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