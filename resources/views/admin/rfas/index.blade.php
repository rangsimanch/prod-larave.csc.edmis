@extends('layouts.admin')
@section('content')
<div class="content">
    @can('rfa_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.rfas.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.rfa.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'Rfa', 'route' => 'admin.rfas.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.rfa.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Rfa text-center">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    Action
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.submit_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.cover_sheet') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.file_upload_1') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.document_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.construction_contract') }}
                                </th>
                                
                                <th>
                                    {{ trans('cruds.rfa.fields.boq') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.worktype') }}
                                </th> 
                                <th>
                                    {{ trans('cruds.rfa.fields.title_eng') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.origin_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfatype.fields.type_code') }}
                                </th>
                            
                                <th>
                                    {{ trans('cruds.rfa.fields.wbs_level_3') }}
                                </th>
                               
                                <th>
                                    {{ trans('cruds.rfa.fields.wbs_level_4') }}
                                </th> 

                                <th>
                                    {{ trans('cruds.rfa.fields.comment_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.commercial_file_upload') }}
                                </th>
                               
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    {{-- <input type="text" name="daterange" id="daterange" class="form-control daterange" value="" autocomplete="off" placeholder="Select Period.."> --}}
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfa_document_statuses as $key => $item)
                                            <option value="{{ $item->status_name }}">{{ $item->status_name }}</option>
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($bo_qs as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\Rfa::WORKTYPE_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfatypes as $key => $item)
                                            <option value="{{ $item->type_code }}">{{ $item->type_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbs_level_threes as $key => $item)
                                            <option value="{{ $item->wbs_level_3_code }}">{{ $item->wbs_level_3_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbslevelfours as $key => $item)
                                            <option value="{{ $item->wbs_level_4_code }}">{{ $item->wbs_level_4_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                               
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfa_comment_statuses as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
@can('rfa_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.rfas.massDestroy') }}",
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
    columnDefs: [
            { width: 200, targets: 7 }
        ],
    ajax: "{{ route('admin.rfas.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'actions', name: '{{ trans('global.actions') }}' ,searchable: false},
{ data: 'submit_date', name: 'submit_date' },
{ data: 'cover_sheet', name: 'cover_sheet' ,sortable: false, searchable: false},
{ data: 'file_upload_1', name: 'file_upload_1', sortable: false, searchable: false},
{ data: 'document_status_status_name', name: 'document_status.status_name'},
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'boq_name', name: 'boq.name'},
{ data: 'worktype', name: 'worktype' },
{ data: 'title_eng', name: 'title_eng' },
{ data: 'title', name: 'title' },
{ data: 'origin_number', name: 'origin_number' },
{ data: 'document_number', name: 'document_number' },
{ data: 'type.type_code', name: 'type.type_code' },
{ data: 'wbs_level_3_wbs_level_3_code', name: 'wbs_level_3.wbs_level_3_code' },
{ data: 'wbs_level_4_wbs_level_4_code', name: 'wbs_level_4.wbs_level_4_code' },
{ data: 'comment_status_name', name: 'comment_status.name' },
{ data: 'commercial_file_upload', name: 'commercial_file_upload', sortable: false, searchable: false },

    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 10,
    
  };
  let table = $('.datatable-Rfa').DataTable(dtOverrideGlobals);
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