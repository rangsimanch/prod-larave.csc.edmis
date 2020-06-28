@extends('layouts.admin')
@section('content')
<div class="content">
    @can('request_for_inspection_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.request-for-inspections.create') }}">
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
                                    {{ trans('cruds.requestForInspection.fields.bill') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.wbs_level_1') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.wbs_level_2') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.wbs_level_3') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.wbs_level_4') }}
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
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($bo_qs as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbs_level_ones as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbs_level_twos as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbs_level_threes as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbslevelfours as $key => $item)
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
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\RequestForInspection::TYPE_OF_WORK_SELECT as $key => $item)
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\RequestForInspection::RESULT_OF_INSPECTION_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($construction_contracts as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
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
{ data: 'bill_name', name: 'bill.name' },
{ data: 'wbs_level_1_name', name: 'wbs_level_1.name' },
{ data: 'wbs_level_2_name', name: 'wbs_level_2.name' },
{ data: 'wbs_level_3_wbs_level_3_name', name: 'wbs_level_3.wbs_level_3_name' },
{ data: 'wbs_level_4_wbs_level_4_name', name: 'wbs_level_4.wbs_level_4_name' },
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
    orderCellsTop: true,
    order: [[ 17, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-RequestForInspection').DataTable(dtOverrideGlobals);
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