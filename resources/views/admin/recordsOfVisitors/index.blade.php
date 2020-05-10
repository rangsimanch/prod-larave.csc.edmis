@extends('layouts.admin')
@section('content')
<div class="content">
    @can('records_of_visitor_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.records-of-visitors.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.recordsOfVisitor.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.recordsOfVisitor.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RecordsOfVisitor">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.recordsOfVisitor.fields.date_of_visit') }}
                                </th>
                                <th>
                                    {{ trans('cruds.recordsOfVisitor.fields.name_of_visitor') }}
                                </th>
                                <th>
                                    {{ trans('cruds.recordsOfVisitor.fields.details') }}
                                </th>
                                <th>
                                    {{ trans('cruds.recordsOfVisitor.fields.file_upload') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
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
@can('records_of_visitor_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.records-of-visitors.massDestroy') }}",
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
    ajax: "{{ route('admin.records-of-visitors.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'date_of_visit', name: 'date_of_visit' },
{ data: 'name_of_visitor', name: 'name_of_visitor' },
{ data: 'details', name: 'details' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  $('.datatable-RecordsOfVisitor').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection