@extends('layouts.admin')
@section('styles')
<style>
    .audit-log-filters input,
    .audit-log-filters select {
        min-width: 90px;
        width: 100%;
    }

    .audit-log-filters select {
        height: 30px;
    }

    .audit-log-date-filter {
        margin-bottom: 4px;
    }
</style>
@endsection
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-AuditLog">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>{{ trans('cruds.auditLog.fields.id') }}</th>
                                    <th>{{ trans('cruds.auditLog.fields.description') }}</th>
                                    <th>{{ trans('cruds.auditLog.fields.subject_id') }}</th>
                                    <th>{{ trans('cruds.auditLog.fields.subject_type') }}</th>
                                    <th>{{ trans('cruds.auditLog.fields.user_id') }}</th>
                                    <th>{{ trans('cruds.auditLog.fields.user_name') }}</th>
                                    <th>{{ trans('cruds.auditLog.fields.user_email') }}</th>
                                    <th>{{ trans('cruds.auditLog.fields.host') }}</th>
                                    <th>{{ trans('cruds.auditLog.fields.created_at') }}</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr class="audit-log-filters">
                                    <td></td>
                                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}" aria-label="{{ trans('cruds.auditLog.fields.id') }}"></td>
                                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}" aria-label="{{ trans('cruds.auditLog.fields.description') }}"></td>
                                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}" aria-label="{{ trans('cruds.auditLog.fields.subject_id') }}"></td>
                                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}" aria-label="{{ trans('cruds.auditLog.fields.subject_type') }}"></td>
                                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}" aria-label="{{ trans('cruds.auditLog.fields.user_id') }}"></td>
                                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}" aria-label="{{ trans('cruds.auditLog.fields.user_name') }}"></td>
                                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}" aria-label="{{ trans('cruds.auditLog.fields.user_email') }}"></td>
                                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}" aria-label="{{ trans('cruds.auditLog.fields.host') }}"></td>
                                    <td>
                                        <input id="audit-log-created-from" data-date-filter="from" class="form-control audit-log-date-filter" type="date" aria-label="{{ trans('cruds.auditLog.fields.created_at') }} From">
                                        <input id="audit-log-created-to" data-date-filter="to" class="form-control audit-log-date-filter" type="date" aria-label="{{ trans('cruds.auditLog.fields.created_at') }} To">
                                    </td>
                                    <td>
                                        <button id="audit-log-clear-filters" type="button" class="btn btn-default btn-xs">
                                            Clear
                                        </button>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        let table = $('.datatable-AuditLog').DataTable({
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 20,
            lengthMenu: [[20], [20]],
            ajax: {
                url: "{{ route('admin.audit-logs.index') }}",
                data: function (data) {
                    data.created_from = $('.audit-log-date-filter[data-date-filter="from"]').filter(function () {
                        return $(this).val() !== '';
                    }).last().val() || '';
                    data.created_to = $('.audit-log-date-filter[data-date-filter="to"]').filter(function () {
                        return $(this).val() !== '';
                    }).last().val() || '';
                }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder', searchable: false, orderable: false },
                { data: 'id', name: 'audit_logs.id' },
                { data: 'description', name: 'audit_logs.description' },
                { data: 'subject_id', name: 'audit_logs.subject_id' },
                { data: 'subject_type', name: 'audit_logs.subject_type' },
                { data: 'user_id', name: 'audit_logs.user_id' },
                { data: 'user_name', name: 'user_name' },
                { data: 'user_email', name: 'user_email' },
                { data: 'host', name: 'audit_logs.host' },
                { data: 'created_at', name: 'audit_logs.created_at' },
                { data: 'actions', name: 'actions', searchable: false, orderable: false }
            ]
        });

        let filterTimer;
        $(document).on('input change', '.audit-log-filters .search', function () {
            let input = this;
            clearTimeout(filterTimer);
            filterTimer = setTimeout(function () {
                table.column($(input).closest('td, th').index()).search($(input).val()).draw();
            }, 300);
        });

        $(document).on('change', '.audit-log-date-filter', function () {
            table.ajax.reload();
        });

        $(document).on('click', '#audit-log-clear-filters', function () {
            $('.audit-log-filters .search').val('');
            $('.audit-log-date-filter').val('');
            $('.dataTables_filter input').val('');
            table.search('').columns().search('').draw();
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection
