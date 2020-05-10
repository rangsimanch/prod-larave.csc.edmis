@extends('layouts.admin')
@section('content')
<div class="content">
    @can('drone_vdo_recorded_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.drone-vdo-recordeds.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.droneVdoRecorded.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.droneVdoRecorded.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DroneVdoRecorded">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.droneVdoRecorded.fields.work_tiltle') }}
                                </th>
                                <th>
                                    {{ trans('cruds.droneVdoRecorded.fields.operation_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.droneVdoRecorded.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.droneVdoRecorded.fields.file_upload') }}
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
@can('drone_vdo_recorded_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.drone-vdo-recordeds.massDestroy') }}",
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
    ajax: "{{ route('admin.drone-vdo-recordeds.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'work_tiltle', name: 'work_tiltle' },
{ data: 'operation_date', name: 'operation_date' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 2, 'desc' ]],
    pageLength: 10,
  };
  $('.datatable-DroneVdoRecorded').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection