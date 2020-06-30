@extends('layouts.admin')
@section('content')
<div class="content">
    @can('non_conformance_report_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.non-conformance-reports.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.nonConformanceReport.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.nonConformanceReport.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-NonConformanceReport">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.ncn_ref') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.corresponding_to') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.response_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.root_cause') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.corrective_action') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.preventive_action') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.ref_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.attachment') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.prepared_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.contractors_project') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.csc_consideration_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.approved_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.csc_disposition_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceReport.fields.file_upload') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($non_conformance_notices as $key => $item)
                                            <option value="{{ $item->ref_no }}">{{ $item->ref_no }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\NonConformanceReport::CSC_CONSIDERATION_STATUS_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\NonConformanceReport::CSC_DISPOSITION_STATUS_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </thead>
                    </table>
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
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('non_conformance_report_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.non-conformance-reports.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.non-conformance-reports.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'ncn_ref_ref_no', name: 'ncn_ref.ref_no' },
{ data: 'corresponding_to', name: 'corresponding_to' },
{ data: 'response_date', name: 'response_date' },
{ data: 'root_cause', name: 'root_cause' },
{ data: 'corrective_action', name: 'corrective_action' },
{ data: 'preventive_action', name: 'preventive_action' },
{ data: 'ref_no', name: 'ref_no' },
{ data: 'attachment', name: 'attachment', sortable: false, searchable: false },
{ data: 'prepared_by_name', name: 'prepared_by.name' },
{ data: 'contractors_project_name', name: 'contractors_project.name' },
{ data: 'csc_consideration_status', name: 'csc_consideration_status' },
{ data: 'approved_by_name', name: 'approved_by.name' },
{ data: 'csc_disposition_status', name: 'csc_disposition_status' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 3, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-NonConformanceReport').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  $('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value
      table
        .column($(this).parent().index())
        .search(value, strict)
        .draw()
  });
});

</script>
@endsection