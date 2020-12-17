@extends('layouts.admin')
@section('content')
<div class="content">
    @can('srt_external_document_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.srt-external-documents.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.srtExternalDocument.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.srtExternalDocument.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SrtExternalDocument">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.id') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.docuement_status') }}
                                </th>
                                <!-- <th>
                                    {{ trans('cruds.srtExternalDocument.fields.constuction_contract') }}
                                </th> -->
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.document_type') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.subject') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.refer_to') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.speed_class') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.objective') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.close_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.file_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.save_for') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.close_by') }}
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($srt_document_statuses as $key => $item)
                                            <option value="{{ $item->title }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <!-- <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($construction_contracts as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                </td> -->
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtExternalDocument::DOCUMENT_TYPE_SELECT as $key => $item)
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
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtExternalDocument::SPEED_CLASS_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtExternalDocument::OBJECTIVE_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtExternalDocument::SAVE_FOR_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
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
@can('srt_external_document_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.srt-external-documents.massDestroy') }}",
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
    ajax: "{{ route('admin.srt-external-documents.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
 { data: 'id', name: 'id', visible: false},
{ data: 'docuement_status_title', name: 'docuement_status.title' },
// { data: 'constuction_contract_code', name: 'constuction_contract.code' },
{ data: 'document_type', name: 'document_type' },
{ data: 'document_number', name: 'document_number' },
{ data: 'subject', name: 'subject' },
{ data: 'refer_to', name: 'refer_to' },
{ data: 'speed_class', name: 'speed_class' },
{ data: 'objective', name: 'objective' },
{ data: 'close_date', name: 'close_date' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'save_for', name: 'save_for' },
{ data: 'close_by_name', name: 'close_by.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-SrtExternalDocument').DataTable(dtOverrideGlobals);
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