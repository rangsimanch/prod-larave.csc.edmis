@extends('layouts.admin')
@section('content')
<div class="content">
    @can('documents_and_assignment_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.documents-and-assignments.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.documentsAndAssignment.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.documentsAndAssignment.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DocumentsAndAssignment">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.documentsAndAssignment.fields.file_name') }}
                                </th>
                                <th>
                                    {{ trans('cruds.documentsAndAssignment.fields.original_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.documentsAndAssignment.fields.receipt_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.documentsAndAssignment.fields.date_of_receipt') }}
                                </th>
                                <th>
                                    {{ trans('cruds.documentsAndAssignment.fields.received_from') }}
                                </th>
                                <th>
                                    {{ trans('cruds.documentsAndAssignment.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.documentsAndAssignment.fields.file_upload') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
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
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($construction_contracts as $key => $item)
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
@can('documents_and_assignment_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.documents-and-assignments.massDestroy') }}",
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
    ajax: "{{ route('admin.documents-and-assignments.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'file_name', name: 'file_name' },
{ data: 'original_no', name: 'original_no' },
{ data: 'receipt_no', name: 'receipt_no' },
{ data: 'date_of_receipt', name: 'date_of_receipt' },
{ data: 'received_from', name: 'received_from' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 4, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-DocumentsAndAssignment').DataTable(dtOverrideGlobals);
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