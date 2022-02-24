@extends('layouts.admin')
@section('content')
<div class="content">
    @can('request_for_information_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.request-for-informations.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.requestForInformation.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.requestForInformation.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RequestForInformation text-center">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    Action
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.created_at') }}
                                </th>
                                <th>
                                    Cover Sheet
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.document_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.title') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.document_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.originator_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.to') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.wbs_level_4') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.wbs_level_5') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.originator_name') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.incoming_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.incoming_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.attachment_files') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.request_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.outgoing_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.outgoing_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.authorised_rep') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.response_organization') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.response_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.file_upload') }}
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
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\RequestForInformation::DOCUMENT_STATUS_SELECT as $key => $item)
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
                                        @foreach($teams as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbs_level_threes as $key => $item)
                                            <option value="{{ $item->wbs_level_3_name }}">{{ $item->wbs_level_3_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbslevelfours as $key => $item)
                                            <option value="{{ $item->wbs_level_4_name }}">{{ $item->wbs_level_4_name }}</option>
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
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($teams as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
@can('request_for_information_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.request-for-informations.massDestroy') }}",
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
    ajax: "{{ route('admin.request-for-informations.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'actions', name: '{{ trans('global.actions') }}' },
{ data: 'created_at', name: 'created_at'}, 
{ data: 'cover_sheet', name: 'cover_sheet'},
{ data: 'document_status', name: 'document_status' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'title', name: 'title' },
{ data: 'document_no', name: 'document_no' },
{ data: 'originator_code', name: 'originator_code' },
{ data: 'date', name: 'date' },
{ data: 'to_code', name: 'to.code' },
{ data: 'wbs_level_4_wbs_level_3_name', name: 'wbs_level_4.wbs_level_3_name' },
{ data: 'wbs_level_5_wbs_level_4_name', name: 'wbs_level_5.wbs_level_4_name' },
{ data: 'originator_name', name: 'originator_name' },
{ data: 'incoming_no', name: 'incoming_no' },
{ data: 'incoming_date', name: 'incoming_date' },
{ data: 'attachment_files', name: 'attachment_files', sortable: false, searchable: false },
{ data: 'request_by_name', name: 'request_by.name' },
{ data: 'outgoing_date', name: 'outgoing_date' },
{ data: 'outgoing_no', name: 'outgoing_no' },
{ data: 'authorised_rep_name', name: 'authorised_rep.name' },
{ data: 'response_organization_code', name: 'response_organization.code' },
{ data: 'response_date', name: 'response_date' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false }
    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 50,
    aLengthMenu: [
        [5, 10, 25, 50, 100, 200, 1000],
        [5, 10, 25, 50, 100, 200, 1000]
    ],
  };
  let table = $('.datatable-RequestForInformation').DataTable(dtOverrideGlobals);
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