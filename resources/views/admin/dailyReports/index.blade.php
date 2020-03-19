@extends('layouts.admin')
@section('content')
<div class="content">
    @can('daily_report_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.daily-reports.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.dailyReport.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.dailyReport.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-DailyReport">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.input_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.documents') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.receive_by') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dailyReport.fields.receive_date') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyReports as $key => $dailyReport)
                                    <tr data-entry-id="{{ $dailyReport->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $dailyReport->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dailyReport->input_date ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($dailyReport->documents as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $dailyReport->receive_by->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dailyReport->receive_date ?? '' }}
                                        </td>
                                        <td>
                                            @can('daily_report_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.daily-reports.show', $dailyReport->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('daily_report_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.daily-reports.edit', $dailyReport->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('daily_report_delete')
                                                <form action="{{ route('admin.daily-reports.destroy', $dailyReport->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
@can('daily_report_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.daily-reports.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-DailyReport:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection