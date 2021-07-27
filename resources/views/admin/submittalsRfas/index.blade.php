@extends('layouts.admin')
@section('content')
<div class="content">
    @can('submittals_rfa_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.submittals-rfas.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.submittalsRfa.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.submittalsRfa.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-SubmittalsRfa">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.item_no') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.description') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.qty_sets') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.review_status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.date_returned') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.remarks') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.on_rfa') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submittalsRfas as $key => $submittalsRfa)
                                    <tr data-entry-id="{{ $submittalsRfa->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $submittalsRfa->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $submittalsRfa->item_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $submittalsRfa->description ?? '' }}
                                        </td>
                                        <td>
                                            {{ $submittalsRfa->qty_sets ?? '' }}
                                        </td>
                                        <td>
                                            {{ $submittalsRfa->review_status->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $submittalsRfa->date_returned ?? '' }}
                                        </td>
                                        <td>
                                            {{ $submittalsRfa->remarks ?? '' }}
                                        </td>
                                        <td>
                                            {{ $submittalsRfa->on_rfa->rfa_code ?? '' }}
                                        </td>
                                        <td>
                                            @can('submittals_rfa_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.submittals-rfas.show', $submittalsRfa->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('submittals_rfa_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.submittals-rfas.edit', $submittalsRfa->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('submittals_rfa_delete')
                                                <form action="{{ route('admin.submittals-rfas.destroy', $submittalsRfa->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('submittals_rfa_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.submittals-rfas.massDestroy') }}",
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
    pageLength: 10,
  });
  $('.datatable-SubmittalsRfa:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection