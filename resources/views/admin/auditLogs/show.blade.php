@extends('layouts.admin')
@section('styles')
<style>
    .audit-summary th {
        width: 220px;
    }

    .audit-value {
        white-space: pre-wrap;
        word-break: break-word;
    }

    .audit-changes {
        table-layout: fixed;
    }

    .audit-changes th,
    .audit-changes td {
        width: 33.33%;
    }
</style>
@endsection
@section('content')
@php
    $formatAuditValue = function ($value) {
        if ($value === null) {
            return '-';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        $json = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $json === false ? '[unavailable]' : $json;
    };
@endphp
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.auditLog.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.audit-logs.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>

                    <h4>{{ trans('cruds.auditLog.fields.summary') }}</h4>
                    <table class="table table-bordered table-striped audit-summary">
                        <tbody>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.model') }}</th>
                                <td>{{ $modelName }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.subject_type') }}</th>
                                <td class="audit-value">{{ $auditLog->subject_type ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.subject_id') }}</th>
                                <td>{{ $auditLog->subject_id ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.action') }}</th>
                                <td>{{ $auditLog->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.user_id') }}</th>
                                <td>{{ $auditLog->user_id ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.user_name') }}</th>
                                <td>{{ optional($auditLog->user)->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.user_email') }}</th>
                                <td>{{ optional($auditLog->user)->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.host') }}</th>
                                <td>{{ $auditLog->host ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.created_at') }}</th>
                                <td>{{ $auditLog->created_at ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.auditLog.fields.id') }}</th>
                                <td>{{ $auditLog->id }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4>{{ trans('cruds.auditLog.fields.changes') }}</h4>
                    @if(count($propertyChanges) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped audit-changes">
                                <thead>
                                    <tr>
                                        <th>{{ trans('cruds.auditLog.fields.field') }}</th>
                                        <th>{{ trans('cruds.auditLog.fields.before') }}</th>
                                        <th>{{ trans('cruds.auditLog.fields.after') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($propertyChanges as $change)
                                        <tr>
                                            <td class="audit-value">{{ $change['field'] }}</td>
                                            <td class="audit-value">{{ $formatAuditValue($change['old']) }}</td>
                                            <td class="audit-value">{{ $formatAuditValue($change['new']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">{{ trans('cruds.auditLog.fields.no_changes') }}</p>
                    @endif

                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.audit-logs.index') }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
