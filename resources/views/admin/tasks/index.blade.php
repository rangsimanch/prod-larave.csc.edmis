@extends('layouts.admin')
@section('content')
<style>
img {
  border-radius: 50%;
}
</style>

<div class="content">
    @can('task_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.tasks.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.task.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.task.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Task">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.img_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.create_by_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.name') }}
                                </th>
                                
                                <th>
                                    {{ trans('cruds.task.fields.tag') }}
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.location') }}
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.due_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.attachment') }}
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.construction_contract') }}
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
@can('task_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tasks.massDestroy') }}",
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
    ajax: "{{ route('admin.tasks.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'img_user', name: 'img_user', sortable: false, searchable: false },
{ data: 'create_by_user_name', name: 'create_by_user.name' },
{ data: 'name', name: 'name' },
// { data: 'description', name: 'description' },
{ data: 'tag', name: 'tags.name' },
{ data: 'location', name: 'location' },
{ data: 'due_date', name: 'due_date' },
{ data: 'status_name', name: 'status.name' },
{ data: 'attachment', name: 'attachment', sortable: false, searchable: false },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
// { data: 'construction_contract.name', name: 'construction_contract.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 6, 'desc' ]],
    pageLength: 10,
  };
  $('.datatable-Task').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection