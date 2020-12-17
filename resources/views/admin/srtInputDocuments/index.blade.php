@extends('layouts.admin')
@section('content')
<div class="content">
    @can('srt_input_document_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.srt-input-documents.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.srtInputDocument.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'SrtInputDocument', 'route' => 'admin.srt-input-documents.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.srtInputDocument.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SrtInputDocument">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.srtExternalDocument.fields.id') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.docuement_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.document_type') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.subject') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.refer_to') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.speed_class') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.objective') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.close_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.file_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.file_upload_2') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.file_upload_3') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.file_upload_4') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.complete_file') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.close_by') }}
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
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtInputDocument::DOCUMENT_TYPE_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
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
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtInputDocument::SPEED_CLASS_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtInputDocument::OBJECTIVE_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
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
@can('srt_input_document_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.srt-input-documents.massDestroy') }}",
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
    ajax: "{{ route('admin.srt-input-documents.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
 { data: 'id', name: 'id', visible: false},
{ data: 'docuement_status_title', name: 'docuement_status.title' },
{ data: 'document_number', name: 'document_number' },
{ data: 'document_type', name: 'document_type' },
{ data: 'subject', name: 'subject' },
{ data: 'refer_to', name: 'refer_to' },
{ data: 'speed_class', name: 'speed_class' },
{ data: 'objective', name: 'objective' },
{ data: 'close_date', name: 'close_date' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'file_upload_2', name: 'file_upload_2', sortable: false, searchable: false },
{ data: 'file_upload_3', name: 'file_upload_3', sortable: false, searchable: false },
{ data: 'file_upload_4', name: 'file_upload_4', sortable: false, searchable: false },
{ data: 'complete_file', name: 'complete_file', sortable: false, searchable: false },
{ data: 'close_by_name', name: 'close_by.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-SrtInputDocument').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
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