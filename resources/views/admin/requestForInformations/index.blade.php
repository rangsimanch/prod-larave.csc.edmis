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
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RequestForInformation">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.to_organization') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.attention_name') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.document_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.title') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.to') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.discipline') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.originator_name') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.cc_to') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.incoming_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.incoming_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.description') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.attachment_file_description') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.attachment_files') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.request_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.outgoing_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.outgoing_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.response') }}
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
                                <th>
                                    {{ trans('cruds.requestForInformation.fields.record') }}
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($construction_contracts as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
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
                                        @foreach($teams as $key => $item)
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
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
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
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
{ data: 'to_organization', name: 'to_organization' },
{ data: 'attention_name', name: 'attention_name' },
{ data: 'document_no', name: 'document_no' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'date', name: 'date' },
{ data: 'title', name: 'title' },
{ data: 'to_code', name: 'to.code' },
{ data: 'discipline', name: 'discipline' },
{ data: 'originator_name', name: 'originator_name' },
{ data: 'cc_to', name: 'cc_to' },
{ data: 'incoming_no', name: 'incoming_no' },
{ data: 'incoming_date', name: 'incoming_date' },
{ data: 'description', name: 'description' },
{ data: 'attachment_file_description', name: 'attachment_file_description' },
{ data: 'attachment_files', name: 'attachment_files', sortable: false, searchable: false },
{ data: 'request_by_name', name: 'request_by.name' },
{ data: 'outgoing_no', name: 'outgoing_no' },
{ data: 'outgoing_date', name: 'outgoing_date' },
{ data: 'response', name: 'response' },
{ data: 'authorised_rep_name', name: 'authorised_rep.name' },
{ data: 'response_organization_code', name: 'response_organization.code' },
{ data: 'response_date', name: 'response_date' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'record', name: 'record' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 5, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-RequestForInformation').DataTable(dtOverrideGlobals);
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