@extends('layouts.admin')
@section('content')
<div class="content">
    @can('rfa_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.rfas.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.rfa.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'Rfa', 'route' => 'admin.rfas.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.rfa.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Rfa">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title_eng') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title_cn') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.rfa_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.review_time') }}
                                </th>   
                                <th>
                                    {{ trans('cruds.rfa.fields.type') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfatype.fields.type_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.wbs_level_3') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.wbs_level_4') }}
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
                                    {{ trans('cruds.rfa.fields.comment_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.information_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.receive_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.note_2') }}
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
                                    {{ trans('cruds.rfa.fields.created_at') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.create_by_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.update_by_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.approve_by_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.updated_at') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.team') }}
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
@can('rfa_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.rfas.massDestroy') }}",
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
    ajax: "{{ route('admin.rfas.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'title_eng', name: 'title_eng' },
{ data: 'title', name: 'title' },
{ data: 'title_cn', name: 'title_cn' },
{ data: 'document_number', name: 'document_number' },
{ data: 'rfa_code', name: 'rfa_code' },
{ data: 'review_time', name: 'review_time' },
{ data: 'type_type_name', name: 'type.type_name' },
{ data: 'type.type_code', name: 'type.type_code' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'wbs_level_3_wbs_level_3_code', name: 'wbs_level_3.wbs_level_3_code' },
{ data: 'wbs_level_4_wbs_level_4_code', name: 'wbs_level_4.wbs_level_4_code' },
{ data: 'submit_date', name: 'submit_date' },
{ data: 'issueby_name', name: 'issueby.name' },
{ data: 'assign_name', name: 'assign.name' },
{ data: 'file_upload_1', name: 'file_upload_1', sortable: false, searchable: false },
{ data: 'comment_by_name', name: 'comment_by.name' },
{ data: 'information_by_name', name: 'information_by.name' },
{ data: 'receive_date', name: 'receive_date' },
{ data: 'note_2', name: 'note_2' },
{ data: 'comment_status_name', name: 'comment_status.name' },
{ data: 'note_3', name: 'note_3' },
{ data: 'for_status_name', name: 'for_status.name' },
{ data: 'document_status_status_name', name: 'document_status.status_name' },
{ data: 'created_at', name: 'created_at' },
{ data: 'create_by_user_name', name: 'create_by_user.name' },
{ data: 'update_by_user_name', name: 'update_by_user.name' },
{ data: 'approve_by_user_name', name: 'approve_by_user.name' },
{ data: 'updated_at', name: 'updated_at' },
{ data: 'team_name', name: 'team.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  $('.datatable-Rfa').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection