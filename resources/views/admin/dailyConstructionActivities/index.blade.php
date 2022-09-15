
@extends('layouts.admin')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
    .descPhoto {
        position: absolute;
        background-color: black;
        top: 20px;
        right:10px;
        color: white;
        padding-left: 20%;
        padding-right: 20%;
        padding-top: 2%;
        padding-bottom: 2%;
        font-size:18px;
        opacity: 0.5;
}

</style>



<div class="content">
    @can('daily_construction_activity_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.daily-construction-activities.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.dailyConstructionActivity.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.dailyConstructionActivity.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable  datatable-DailyConstructionActivity">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th style="display:none;">
                                    {{ trans('cruds.dailyConstructionActivity.fields.id') }}
                                </th>
                                <th>
                                    {{ trans('cruds.dailyConstructionActivity.fields.work_title') }}
                                </th>
                                <th>
                                    {{ trans('cruds.dailyConstructionActivity.fields.operation_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.dailyConstructionActivity.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.dailyConstructionActivity.fields.image_upload') }}
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
@can('daily_construction_activity_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.daily-construction-activities.massDestroy') }}",
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
    ajax: "{{ route('admin.daily-construction-activities.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
      { data: 'id', name: 'id' , visible: false},
{ data: 'work_title', name: 'work_title' },
{ data: 'operation_date', name: 'operation_date' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'image_upload', name: 'image_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 1,
    sPaginationType: "listbox",
  };
  $('.datatable-DailyConstructionActivity').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});


</script>

@endsection
