@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.interimPaymentMeeting.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.interim-payment-meetings.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.interimPaymentMeeting.fields.meeting_name') }}
                                    </th>
                                    <td>
                                        {{ $interimPaymentMeeting->meeting_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.interimPaymentMeeting.fields.meeting_no') }}
                                    </th>
                                    <td>
                                        {{ $interimPaymentMeeting->meeting_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.interimPaymentMeeting.fields.date') }}
                                    </th>
                                    <td>
                                        {{ $interimPaymentMeeting->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.interimPaymentMeeting.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $interimPaymentMeeting->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.interimPaymentMeeting.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $interimPaymentMeeting->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.interimPaymentMeeting.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($interimPaymentMeeting->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.interim-payment-meetings.index') }}">
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