@extends('layouts.admin')
@section('content')
<div class="content">
    @can('close_out_main_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.close-out-mains.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.closeOutMain.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'CloseOutMain', 'route' => 'admin.close-out-mains.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.closeOutMain.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-CloseOutMain text-center items-center">
                        <thead>
                            <tr style="font-size:12px">
                                <th width="10">

                                </th>                                
                                 <th>
                                </th>
                                 <th>
                                    {{ trans('global.created_at') }}

                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.closeout_subject') }}
                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.detail') }}
                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.quantity') }}
                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.ref_documents') }}
                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.final_file') }}
                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.closeout_url') }}
                                </th>
                                <th>
                                    {{ trans('cruds.closeOutMain.fields.ref_rfa_text') }}
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
                                        @foreach(App\CloseOutMain::STATUS_SELECT as $key => $item)
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($close_out_descriptions as $key => $item)
                                            <option value="{{ $item->subject }}">{{ $item->subject }}</option>
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

<div class="modal fade" id="closeoutUrlModal" tabindex="-1" role="dialog" aria-labelledby="closeoutUrlModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('global.close') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="closeoutUrlModalLabel">
                    <i class="fa fa-link" aria-hidden="true"></i>
                    {{ trans('cruds.closeOutMain.fields.closeout_url') }}
                </h4>
            </div>
            <div class="modal-body p-0" style="max-height: 60vh; overflow-y: auto;">
                <div class="table-responsive mb-0">
                    <table class="table table table-hover mb-0" id="closeoutUrlTable">
                        <thead class="bg-light">
                            <tr>
                                <th style="width:80%">{{ trans('cruds.closeOutMain.fields.closeout_url') }}</th>
                                <th style="width:20%" class="text-center items-center">URL</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('global.close') }}</button>
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
@can('close_out_main_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.close-out-mains.massDestroy') }}",
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
    ajax: "{{ route('admin.close-out-mains.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'actions', name: '{{ trans('global.actions') }}' ,searchable: false},
{ data: 'created_at', name: 'created_at'},
{ data: 'status', name: 'status' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'closeout_subject_subject', name: 'closeout_subject.subject' },
{ data: 'detail', name: 'detail' },
{ data: 'quantity', name: 'quantity' },
{ data: 'ref_documents', name: 'ref_documents' },
{ data: 'final_file', name: 'final_file', sortable: false, searchable: false },
{ data: 'closeout_url', name: 'closeout_urls.filename' },
{ data: 'ref_rfa_text', name: 'ref_rfa_text' },
    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-CloseOutMain').DataTable(dtOverrideGlobals);
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

$('.datatable-CloseOutMain').on('click', '.view-closeout-urls', function () {
    let urls = [];
    const raw = $(this).attr('data-closeout-urls');
    if (raw) {
        try {
            const decoded = decodeURIComponent(raw);
            urls = JSON.parse(decoded);
        } catch (e) {
            urls = [];
        }
    }

    const title = $(this).data('title') || '{{ trans('cruds.closeOutMain.fields.closeout_url') }}';
    const modal = $('#closeoutUrlModal');
    modal.find('#closeoutUrlModalLabel').text(title);

    const tableBody = modal.find('#closeoutUrlTable tbody');
    tableBody.empty();

    if (!urls.length) {
        tableBody.append('<tr><td colspan="2" class="text-center text-muted">{{ trans('global.no_entries_in_table') }}</td></tr>');
    } else {
        urls.forEach(function (item) {
            const safeName = $('<div>').text(item.filename || '').html();
            const safeUrl = $('<div>').text(item.url || '').html();
            const link = item.url
                ? '<a class="btn btn-sm btn-outline-primary" href="' + safeUrl + '" target="_blank" rel="noopener noreferrer"><i class="fa fa-external-link"></i> {{ trans('global.open') }}</a>'
                : '<span class="text-muted">—</span>';

            tableBody.append(
                '<tr>' +
                    '<td><div class="font-weight-bold">' + safeName + '</div>' + (item.url ? '<div class="small text-break text-muted">' + safeUrl + '</div>' : '') + '</td>' +
                    '<td class="align-middle text-md text-center items-center">' + link + '</td>' +
                '</tr>'
            );
        });
    }

    modal.modal('show');
});

});

</script>
@endsection