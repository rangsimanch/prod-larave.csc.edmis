@extends('layouts.admin')
@section('content')
<div class="content">
    @can('letter_incoming_cec_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.letter-incoming-cecs.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.letterIncomingCec.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.letterIncomingCec.title_singular') }} {{ trans('global.list') }}
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
                                    {{ trans('cruds.addLetter.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.addLetter.fields.letter_upload') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
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
@can('letter_incoming_cec_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.letter-incoming-cecs.massDestroy') }}",
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
    ajax: "{{ route('admin.letter-incoming-cecs.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'title', name: 'title' },
{ data: 'letter_type_type_title', name: 'letter_type.type_title' },
{ data: 'letter_no', name: 'letter_no' },
{ data: 'sender_code', name: 'sender.code' },
{ data: 'sent_date', name: 'sent_date' },
{ data: 'receiver_code', name: 'receiver.code' },
{ data: 'received_date', name: 'received_date' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'letter_upload', name: 'letter_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 5, 'desc' ]],
    pageLength: 25,
  };
  $('.datatable-AddLetter').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection