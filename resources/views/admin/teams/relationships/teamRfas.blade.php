@can('rfa_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.rfas.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.rfa.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.rfa.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Rfa">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.document_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.rfa_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfatype.fields.type_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.submit_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.issueby') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.assign') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.file_upload_1') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.create_by') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.action_by') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.comment_by') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.information_by') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.receive_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.comment_status') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.note_3') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.for_status') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.document_status') }}
                        </th>
                        <th>
                            {{ trans('cruds.rfa.fields.indenture') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rfas as $key => $rfa)
                        <tr data-entry-id="{{ $rfa->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $rfa->id ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->title ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->document_number ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->rfa_code ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->type->type_name ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->type->type_code ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->submit_date ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->issueby->name ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->assign->name ?? '' }}
                            </td>
                            <td>
                                @foreach($rfa->file_upload_1 as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                {{ $rfa->create_by->name ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->action_by->name ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->comment_by->name ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->information_by->name ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->receive_date ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->comment_status->name ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->note_3 ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->for_status->name ?? '' }}
                            </td>
                            <td>
                                {{ $rfa->document_status->status_name ?? '' }}
                            </td>
                            <td>
                                @foreach($rfa->indentures as $key => $item)
                                    <span class="badge badge-info">{{ $item->code }}</span>
                                @endforeach
                            </td>
                            <td>
                                @can('rfa_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.rfas.show', $rfa->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('rfa_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.rfas.edit', $rfa->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('rfa_delete')
                                    <form action="{{ route('admin.rfas.destroy', $rfa->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('rfa_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.rfas.massDestroy') }}",
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
  $('.datatable-Rfa:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection