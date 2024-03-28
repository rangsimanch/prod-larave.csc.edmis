@extends('layouts.admin')
@section('content')
<div class="content">
    @can('add_letter_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.add-letters.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.addLetter.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'AddLetter', 'route' => 'admin.add-letters.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.itdCrecInbox.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AddLetter">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    Actions
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.letter_type') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.topic_category') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.title') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.letter_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.sent_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.sender') }}
                                </th>
                                 <th>
                                    {{ trans('cruds.addLetter.fields.receiver') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.received_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.cc_to') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.letter_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.responsible') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.start_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.complete_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.processing_time') }}
                                </th>
                               
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\AddLetter::STATUS_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\AddLetter::LETTER_TYPE_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($letter_subject_types as $key => $item)
                                            <option value="{{ $item->subject_name }}">{{ $item->subject_name }}</option>
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
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($teams as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($teams as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($teams as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
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
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
    ajax: "{{ route('admin.itd-crec-inboxes.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'actions', name: '{{ trans('global.actions') }}' },
{ data: 'status', name: 'status' },
{ data: 'letter_type', name: 'letter_type' },
{ data: 'topic_category', name: 'topic_categories.subject_name' },
{ data: 'title', name: 'title' },
{ data: 'letter_no', name: 'letter_no' },
{ data: 'sent_date', name: 'sent_date' },
{ data: 'sender_code', name: 'sender.code' },
{ data: 'receiver_code', name: 'receiver.code' },
{ data: 'received_date', name: 'received_date' },
{ data: 'cc_to', name: 'cc_tos.code' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'letter_upload', name: 'letter_upload', sortable: false, searchable: false },
{ data: 'responsible_name', name: 'responsible.name' },
{ data: 'start_date', name: 'start_date' },
{ data: 'complete_date', name: 'complete_date' },
{ data: 'processing_time', name: 'processing_time' },
    ],
    orderCellsTop: true,
    order: [[ 7, 'desc' ]],
    pageLength: 25,
    aLengthMenu: [
        [5, 10, 25, 50, 100, 200, 1000],
        [5, 10, 25, 50, 100, 200, 1000]
    ],
  };
  let table = $('.datatable-AddLetter').DataTable(dtOverrideGlobals);
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