@extends('layouts.admin')
@section('content')
<div class="content">
    @can('request_for_inspection_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.request-for-inspections.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.requestForInspection.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.requestForInspection.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RequestForInspection">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.bill_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.subject') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.item_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.ref_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.inspection_date_time') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.contact_person') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.type_of_work') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.location') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.ref_specification') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.requested_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.result_of_inspection') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.files_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.created_at') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.construction_contract') }}
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
@can('request_for_inspection_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.request-for-inspections.massDestroy') }}",
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
    ajax: "{{ route('admin.request-for-inspections.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'bill_no', name: 'bill_no' },
{ data: 'subject', name: 'subject' },
{ data: 'item_no', name: 'item_no' },
{ data: 'ref_no', name: 'ref_no' },
{ data: 'inspection_date_time', name: 'inspection_date_time' },
{ data: 'contact_person_name', name: 'contact_person.name' },
{ data: 'type_of_work', name: 'type_of_work' },
{ data: 'location', name: 'location' },
{ data: 'ref_specification', name: 'ref_specification' },
{ data: 'requested_by_name', name: 'requested_by.name' },
{ data: 'result_of_inspection', name: 'result_of_inspection' },
{ data: 'files_upload', name: 'files_upload', sortable: false, searchable: false },
{ data: 'created_at', name: 'created_at' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 13, 'desc' ]],
    pageLength: 100,
  };
  $('.datatable-RequestForInspection').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection