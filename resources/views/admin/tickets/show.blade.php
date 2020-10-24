@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.ticket.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.tickets.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ticket.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $ticket->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ticket.fields.ticket_code') }}
                                    </th>
                                    <td>
                                        {{ $ticket->ticket_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ticket.fields.request_type') }}
                                    </th>
                                    <td>
                                        {{ App\Ticket::REQUEST_TYPE_SELECT[$ticket->request_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ticket.fields.module') }}
                                    </th>
                                    <td>
                                        {{ App\Ticket::MODULE_SELECT[$ticket->module] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ticket.fields.detail') }}
                                    </th>
                                    <td>
                                        {{ $ticket->detail }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ticket.fields.attachment') }}
                                    </th>
                                    <td>
                                        @foreach($ticket->attachment as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ticket.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Ticket::STATUS_SELECT[$ticket->status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.tickets.index') }}">
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