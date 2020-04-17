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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-DailyRequest">
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
                                    <!-- <th>
                                        {{ trans('cruds.dailyRequest.fields.acknowledge_date') }}
                                    </th> -->
                                    <th> 
                                        {{ trans('cruds.dailyRequest.fields.constuction_contract') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyRequests as $key => $dailyRequest)
                                    <tr data-entry-id="{{ $dailyRequest->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $dailyRequest->input_date ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($dailyRequest->documents as $key => $media)
                                                <a  target="_blank" href="{{ $media->getUrl() }}">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $dailyRequest->document_code ?? '' }}
                                        </td> 
                                        <td>
                                            {{ $dailyRequest->receive_by->name ?? '' }}
                                        </td>
                                        <!-- <td>
                                            {{ $dailyRequest->acknowledge_date ?? '' }}
                                        </td> -->
                                        <td>
                                            {{ $dailyRequest->constuction_contract->code ?? '' }}
                                        </td>
                                        <td>
                                            @can('daily_request_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.daily-requests.show', $dailyRequest->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('daily_request_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.daily-requests.edit', $dailyRequest->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('daily_request_delete')
                                                <form action="{{ route('admin.daily-requests.destroy', $dailyRequest->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('daily_request_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.daily-requests.massDestroy') }}",
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
    order: [[ 1, 'asc' ]],
    pageLength: 100,
  });
  $('.datatable-DailyRequest:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>


@endsection