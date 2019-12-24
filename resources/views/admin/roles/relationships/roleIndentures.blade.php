@can('indenture_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.indentures.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.indenture.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.indenture.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Indenture">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.indenture.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.indenture.fields.indenture_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.indenture.fields.indenture_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.indenture.fields.start_dk') }}
                        </th>
                        <th>
                            {{ trans('cruds.indenture.fields.end_dk') }}
                        </th>
                        <th>
                            {{ trans('cruds.indenture.fields.role') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($indentures as $key => $indenture)
                        <tr data-entry-id="{{ $indenture->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $indenture->id ?? '' }}
                            </td>
                            <td>
                                {{ $indenture->indenture_name ?? '' }}
                            </td>
                            <td>
                                {{ $indenture->indenture_code ?? '' }}
                            </td>
                            <td>
                                {{ $indenture->start_dk ?? '' }}
                            </td>
                            <td>
                                {{ $indenture->end_dk ?? '' }}
                            </td>
                            <td>
                                @foreach($indenture->roles as $key => $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                @can('indenture_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.indentures.show', $indenture->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('indenture_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.indentures.edit', $indenture->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('indenture_delete')
                                    <form action="{{ route('admin.indentures.destroy', $indenture->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('indenture_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.indentures.massDestroy') }}",
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
  $('.datatable-Indenture:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection