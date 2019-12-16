@can('file_manager_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.file-managers.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.fileManager.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.fileManager.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-FileManager">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.fileManager.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.fileManager.fields.file_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.fileManager.fields.code') }}
                        </th>
                        <th>
                            {{ trans('cruds.fileManager.fields.file_upload') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fileManagers as $key => $fileManager)
                        <tr data-entry-id="{{ $fileManager->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $fileManager->id ?? '' }}
                            </td>
                            <td>
                                {{ $fileManager->file_name ?? '' }}
                            </td>
                            <td>
                                {{ $fileManager->code ?? '' }}
                            </td>
                            <td>
                                @foreach($fileManager->file_upload as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('file_manager_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.file-managers.show', $fileManager->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('file_manager_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.file-managers.edit', $fileManager->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('file_manager_delete')
                                    <form action="{{ route('admin.file-managers.destroy', $fileManager->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('file_manager_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.file-managers.massDestroy') }}",
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
  $('.datatable-FileManager:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection