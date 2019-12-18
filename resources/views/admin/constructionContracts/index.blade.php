@extends('layouts.admin')
@section('content')
<div class="content">
    @can('construction_contract_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.construction-contracts.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.constructionContract.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.constructionContract.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ConstructionContract">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.code') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_start_1') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_end_1') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_start_2') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_end_2') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_start_3') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_end_3') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.roadway_km') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.tollway_km') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.total_distance_km') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.budget') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($constructionContracts as $key => $constructionContract)
                                    <tr data-entry-id="{{ $constructionContract->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $constructionContract->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->dk_start_1 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->dk_end_1 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->dk_start_2 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->dk_end_2 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->dk_start_3 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->dk_end_3 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->roadway_km ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->tollway_km ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->total_distance_km ?? '' }}
                                        </td>
                                        <td>
                                            {{ $constructionContract->budget ?? '' }}
                                        </td>
                                        <td>
                                            @can('construction_contract_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.construction-contracts.show', $constructionContract->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('construction_contract_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.construction-contracts.edit', $constructionContract->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('construction_contract_delete')
                                                <form action="{{ route('admin.construction-contracts.destroy', $constructionContract->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('construction_contract_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.construction-contracts.massDestroy') }}",
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
  $('.datatable-ConstructionContract:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection