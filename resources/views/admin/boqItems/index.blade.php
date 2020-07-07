@extends('layouts.admin')
@section('content')
<div class="content">
    @can('boq_item_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.boq-items.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.boqItem.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.boqItem.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-BoqItem">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.boq') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.name') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.unit') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.quantity') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.unit_rate') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.amount') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.factor_f') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.unit_rate_x_ff') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.total_amount') }}
                                </th>
                                <th>
                                    {{ trans('cruds.boqItem.fields.remark') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($bo_qs as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                </td>
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
@can('boq_item_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.boq-items.massDestroy') }}",
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
    ajax: "{{ route('admin.boq-items.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'boq_name', name: 'boq.name' },
{ data: 'code', name: 'code' },
{ data: 'name', name: 'name' },
{ data: 'unit', name: 'unit' },
{ data: 'quantity', name: 'quantity' },
{ data: 'unit_rate', name: 'unit_rate' },
{ data: 'amount', name: 'amount' },
{ data: 'factor_f', name: 'factor_f' },
{ data: 'unit_rate_x_ff', name: 'unit_rate_x_ff' },
{ data: 'total_amount', name: 'total_amount' },
{ data: 'remark', name: 'remark' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'asc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-BoqItem').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  $('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value
      table
        .column($(this).parent().index())
        .search(value, strict)
        .draw()
  });
});

</script>
@endsection