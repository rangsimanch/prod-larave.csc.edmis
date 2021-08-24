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
                                    {{ trans('cruds.rfa.fields.action') }}
                                </th>

                                <th>
                                    {{ trans('cruds.rfa.fields.created_at') }}
                                </th>
                                
                                <th>
                                    {{ trans('cruds.rfa.fields.cover_sheet') }}
                                </th> 

                                <th>
                                    {{ trans('cruds.rfa.fields.document_status') }}
                                </th>
                                
                                <th>
                                    {{ trans('cruds.rfa.fields.file_upload_1') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.boq') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title_eng') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title_cn') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.origin_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.rfa_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.submit_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.issueby') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.assign') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.action_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.comment_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.information_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.receive_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.comment_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.create_by_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.commercial_file_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.updated_at') }}
                                </th>
                              
                            </tr>
                            <tr>
                                <td>
                                </td>

                                <td>
                                </td>

                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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

                                <td>
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                </td>
                                
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
    ajax: "{{ route('admin.rfas.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'actions', name: '{{ trans('global.actions') }}' },
{ data: 'created_at', name: 'created_at'},
{ data: 'cover_sheet', name: 'cover_sheet', sortable: false, searchable: false }, // 3
{ data: 'document_status_status_name', name: 'document_status.status_name' ,sortable: false},
{ data: 'file_upload_1', name: 'file_upload_1', sortable: false, searchable: false },
{ data: 'boq_name', name: 'boq.name' ,sortable: false},
{ data: 'title_eng', name: 'title_eng' ,sortable: false},
{ data: 'title', name: 'title' ,sortable: false},
{ data: 'title_cn', name: 'title_cn' ,sortable: false},
{ data: 'origin_number', name: 'origin_number' },
{ data: 'document_number', name: 'document_number' ,sortable: false},
{ data: 'rfa_code', name: 'rfa_code' ,sortable: false},
{ data: 'submit_date', name: 'submit_date' ,sortable: false},
{ data: 'issueby_name', name: 'issueby.name' ,visible: false,sortable: false},
{ data: 'assign_name', name: 'assign.name' ,visible: false,sortable: false},
{ data: 'action_by_name', name: 'action_by.name' ,visible: false,sortable: false},
{ data: 'comment_by_name', name: 'comment_by.name' ,visible: false,sortable: false},
{ data: 'information_by_name', name: 'information_by.name' ,visible: false,sortable: false},
{ data: 'receive_date', name: 'receive_date' ,visible: false,sortable: false},
{ data: 'comment_status_name', name: 'comment_status.name' ,sortable: false},
{ data: 'create_by_user_name', name: 'create_by_user.name' ,visible: false,sortable: false},
{ data: 'commercial_file_upload', name: 'commercial_file_upload', sortable: false, searchable: false },
{ data: 'updated_at', name: 'updated_at' ,visible: false},
    ],
    order: [[ 2, 'desc' ]],
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    pageLength: 10,
    sPaginationType: "full_numbers",
    scrollY : 450,
    scrollX : "true",
    fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
        }
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