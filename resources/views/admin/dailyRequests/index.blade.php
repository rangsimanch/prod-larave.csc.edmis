@extends('layouts.admin')
@section('content')
<div class="content">
    @can('daily_request_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.daily-requests.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.dailyRequest.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.dailyRequest.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DailyRequest">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.dailyRequest.fields.input_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.dailyRequest.fields.documents') }}
                                </th>
                                <th>
                                    {{ trans('cruds.dailyRequest.fields.document_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.dailyRequest.fields.receive_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.dailyRequest.fields.acknowledge_date') }}
                                </th>
                                <!-- <th>
                                    {{ trans('cruds.dailyRequest.fields.constuction_contract') }}
                                </th> -->
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
@can('daily_request_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.daily-requests.massDestroy') }}",
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
    ajax: "{{ route('admin.daily-requests.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'input_date', name: 'input_date' },
{ data: 'documents', name: 'documents', sortable: false, searchable: false },
{ data: 'document_code', name: 'document_code' },
{ data: 'receive_by_name', name: 'receive_by.name' },
{ data: 'acknowledge_date', name: 'acknowledge_date' },
// { data: 'constuction_contract_code', name: 'constuction_contract.code' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  $('.datatable-DailyRequest').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection