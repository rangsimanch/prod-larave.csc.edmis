@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.meetingWeekly.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.meeting-weeklies.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.meetingWeekly.fields.meeting_name') }}
                                    </th>
                                    <td>
                                        {{ $meetingWeekly->meeting_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.meetingWeekly.fields.meeting_no') }}
                                    </th>
                                    <td>
                                        {{ $meetingWeekly->meeting_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.meetingWeekly.fields.date') }}
                                    </th>
                                    <td>
                                        {{ $meetingWeekly->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.meetingWeekly.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $meetingWeekly->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.meetingWeekly.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $meetingWeekly->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.meetingWeekly.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($meetingWeekly->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.meeting-weeklies.index') }}">
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