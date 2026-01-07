<div class="content">
    @can('close_out_main_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.close-out-mains.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.closeOutMain.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.closeOutMain.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-closeoutUrlCloseOutMains">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.construction_contract') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.closeout_subject') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.detail') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.quantity') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.ref_documents') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.final_file') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.closeout_url') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.ref_rfa') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.ref_rfa_text') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($closeOutMains as $key => $closeOutMain)
                                    <tr data-entry-id="{{ $closeOutMain->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ App\CloseOutMain::STATUS_SELECT[$closeOutMain->status] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $closeOutMain->construction_contract->code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $closeOutMain->closeout_subject->subject ?? '' }}
                                        </td>
                                        <td>
                                            {{ $closeOutMain->detail ?? '' }}
                                        </td>
                                        <td>
                                            {{ $closeOutMain->quantity ?? '' }}
                                        </td>
                                        <td>
                                            {{ $closeOutMain->ref_documents ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($closeOutMain->final_file as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($closeOutMain->closeout_urls as $key => $item)
                                                <span class="label label-info label-many">{{ $item->filename }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($closeOutMain->ref_rfas as $key => $item)
                                                <span class="label label-info label-many">{{ $item->origin_number }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $closeOutMain->ref_rfa_text ?? '' }}
                                        </td>
                                        <td>
                                            @can('close_out_main_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.close-out-mains.show', $closeOutMain->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('close_out_main_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.close-out-mains.edit', $closeOutMain->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('close_out_main_delete')
                                                <form action="{{ route('admin.close-out-mains.destroy', $closeOutMain->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('close_out_main_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.close-out-mains.massDestroy') }}",
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
    orderCellsTop: true,
    order: [[ 2, 'asc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-closeoutUrlCloseOutMains:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection