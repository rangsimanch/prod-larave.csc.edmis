@extends('layouts.admin')
@section('content')
<div class="content">
    @can('non_conformance_notice_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.non-conformance-notices.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.nonConformanceNotice.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.nonConformanceNotice.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-NonConformanceNotice">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.subject') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.description') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.ref_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.submit_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.attachment') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.attachment_description') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.status_ncn') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.csc_issuers') }}
                                </th>
                                <th>
                                    {{ trans('cruds.nonConformanceNotice.fields.file_upload') }}
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
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\NonConformanceNotice::STATUS_NCN_SELECT as $key => $item)
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
@can('non_conformance_notice_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.non-conformance-notices.massDestroy') }}",
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
    ajax: "{{ route('admin.non-conformance-notices.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'subject', name: 'subject' },
{ data: 'description', name: 'description' },
{ data: 'ref_no', name: 'ref_no' },
{ data: 'submit_date', name: 'submit_date' },
{ data: 'attachment', name: 'attachment', sortable: false, searchable: false },
{ data: 'attachment_description', name: 'attachment_description' },
{ data: 'status_ncn', name: 'status_ncn' },
{ data: 'csc_issuers_name', name: 'csc_issuers.name' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 4, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-NonConformanceNotice').DataTable(dtOverrideGlobals);
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