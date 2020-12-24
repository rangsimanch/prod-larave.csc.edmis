@extends('layouts.admin')
@section('content')
<div class="content">
    @can('srt_head_office_document_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.srt-head-office-documents.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.srtHeadOfficeDocument.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'SrtHeadOfficeDocument', 'route' => 'admin.srt-head-office-documents.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.srtHeadOfficeDocument.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SrtHeadOfficeDocument">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.refer_documents') }}
                                </th> 
                                <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.refer_documents') }}
                                </th>

                               
                                <th>
                                    {{ trans('cruds.srtInputDocument.fields.subject') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.process_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.special_command') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.finished_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.operator') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.practice_notes') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.note') }}
                                </th>
                                <!-- <th>
                                    {{ trans('cruds.srtHeadOfficeDocument.fields.file_upload') }}
                                </th> -->
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($srt_input_documents as $key => $item)
                                            <option value="{{ $item->document_number }}">{{ $item->document_number }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                <td>
                                </td>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtHeadOfficeDocument::SPECIAL_COMMAND_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
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
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <!-- <td>
                                </td> -->
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
@can('srt_head_office_document_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.srt-head-office-documents.massDestroy') }}",
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
    ajax: "{{ route('admin.srt-head-office-documents.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id', visible: false},
{ data: 'refer_documents.file_upload', name: 'refer_documents.file_upload', sortable: false, searchable: false },
{ data: 'refer_documents.subject', name: 'refer_documents.subject' },
{ data: 'process_date', name: 'process_date' },
{ data: 'special_command', name: 'special_command' },
{ data: 'finished_date', name: 'finished_date' },
{ data: 'operator', name: 'operators.name' },
{ data: 'practice_notes', name: 'practice_notes' },
{ data: 'note', name: 'note' },
// { data: 'file_upload', name: 'file_upload', sortable: false, searchable: false, visible: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-SrtHeadOfficeDocument').DataTable(dtOverrideGlobals);
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