@extends('layouts.admin')
@section('content')
<div class="content">
    @can('srt_other_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.srt-others.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.srtOther.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.srtOther.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SrtOther">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.constuction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.document_type') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.subject') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.refer_to') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.from_text') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.to_text') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.speed_class') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.objective') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.close_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.file_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.save_for') }}
                                </th>
                                <th>
                                    {{ trans('cruds.srtOther.fields.close_by_text') }}
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
                                        @foreach($construction_contracts as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtOther::DOCUMENT_TYPE_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
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
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtOther::SPEED_CLASS_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtOther::OBJECTIVE_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SrtOther::SAVE_FOR_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
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
@can('srt_other_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.srt-others.massDestroy') }}",
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
    ajax: "{{ route('admin.srt-others.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'constuction_contract_code', name: 'constuction_contract.code' },
{ data: 'document_type', name: 'document_type' },
{ data: 'document_number', name: 'document_number' },
{ data: 'subject', name: 'subject' },
{ data: 'refer_to', name: 'refer_to' },
{ data: 'from_text', name: 'from_text' },
{ data: 'to_text', name: 'to_text' },
{ data: 'speed_class', name: 'speed_class' },
{ data: 'objective', name: 'objective' },
{ data: 'close_date', name: 'close_date' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'save_for', name: 'save_for' },
{ data: 'close_by_text', name: 'close_by_text' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 10, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-SrtOther').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
});

</script>
@endsection