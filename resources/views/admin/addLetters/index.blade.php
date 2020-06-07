@extends('layouts.admin')
@section('content')
<div class="content">
    @can('add_letter_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.add-letters.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.addLetter.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.addLetter.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AddLetter">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.title') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.letter_type') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.letter_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.sender') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.sent_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.receiver') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.received_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.cc_srt') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.cc_pmc') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.cc_csc') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.cc_cec') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.letter_upload') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($letter_types as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($teams as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($teams as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($construction_contracts as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
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
@can('add_letter_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.add-letters.massDestroy') }}",
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
    ajax: "{{ route('admin.add-letters.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'title', name: 'title' },
{ data: 'letter_type_type_title', name: 'letter_type.type_title' },
{ data: 'letter_no', name: 'letter_no' },
{ data: 'sender_code', name: 'sender.code' },
{ data: 'sent_date', name: 'sent_date' },
{ data: 'receiver_code', name: 'receiver.code' },
{ data: 'received_date', name: 'received_date' },
{ data: 'cc_srt', name: 'cc_srt' },
{ data: 'cc_pmc', name: 'cc_pmc' },
{ data: 'cc_csc', name: 'cc_csc' },
{ data: 'cc_cec', name: 'cc_cec' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'letter_upload', name: 'letter_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 5, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-AddLetter').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
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