@extends('layouts.admin')
@section('content')
<div class="content">
    @can('ncr_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.ncrs.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.ncr.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'Ncr', 'route' => 'admin.ncrs.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.ncr.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Ncr text-center">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    Action
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.created_at') }}
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.documents_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.acceptance_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.file_attachment') }}
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.prepared_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.construction_specialist') }}
                                </th>
                                <th>
                                    {{ trans('cruds.ncr.fields.leader') }}
                                </th>
                                
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\Ncr::DOCUMENTS_STATUS_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($construction_contracts as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>

                                <td>
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
@can('ncr_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ncrs.massDestroy') }}",
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
    ajax: "{{ route('admin.ncrs.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'actions', name: '{{ trans('global.actions') }}' },
{ data: 'created_at', name: 'created_at' },
{ data: 'documents_status', name: 'documents_status' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'document_number', name: 'document_number' },
{ data: 'acceptance_date', name: 'acceptance_date' },
{ data: 'file_attachment', name: 'file_attachment', sortable: false, searchable: false },
{ data: 'prepared_by_name', name: 'prepared_by.name' },
{ data: 'construction_specialist_name', name: 'construction_specialist.name' },
{ data: 'leader_name', name: 'leader.name' },
    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 25,
    aLengthMenu: [
        [5, 10, 25, 50, 100, 200, 1000],
        [5, 10, 25, 50, 100, 200, 1000]
    ],
  };
  let table = $('.datatable-Ncr').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
});

</script>
@endsection