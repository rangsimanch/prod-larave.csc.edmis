@extends('layouts.admin')
@section('content')
<div class="content">
    @can('meeting_monthly_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.meeting-monthlies.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.meetingMonthly.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.meetingMonthly.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-MeetingMonthly">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.meetingMonthly.fields.meeting_name') }}
                                </th>
                                <th>
                                    {{ trans('cruds.meetingMonthly.fields.meeting_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.meetingMonthly.fields.date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.meetingMonthly.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.meetingMonthly.fields.note') }}
                                </th>
                                <th>
                                    {{ trans('cruds.meetingMonthly.fields.file_upload') }}
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
@can('meeting_monthly_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.meeting-monthlies.massDestroy') }}",
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
    ajax: "{{ route('admin.meeting-monthlies.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'meeting_name', name: 'meeting_name' },
{ data: 'meeting_no', name: 'meeting_no' },
{ data: 'date', name: 'date' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'note', name: 'note' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 3, 'desc' ]],
    pageLength: 10,
  };
  $('.datatable-MeetingMonthly').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection