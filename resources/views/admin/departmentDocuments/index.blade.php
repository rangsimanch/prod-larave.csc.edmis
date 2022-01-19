@extends('layouts.admin')
@section('content')
<div class="content">
    @can('department_document_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.department-documents.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.departmentDocument.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.departmentDocument.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DepartmentDocument">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.departmentDocument.fields.document_name') }}
                                </th>
                                <th>
                                    {{ trans('cruds.departmentDocument.fields.tag') }}
                                </th>
                                <th>
                                    {{ trans('cruds.departmentDocument.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.departmentDocument.fields.download') }}
                                </th>
                                <th>
                                    {{ trans('cruds.departmentDocument.fields.example_file') }}
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
@can('department_document_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.department-documents.massDestroy') }}",
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
    ajax: "{{ route('admin.department-documents.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'document_name', name: 'document_name' },
{ data: 'tag', name: 'tags.tag_name' },
{ data: 'construction_contract_code', name: 'construction_contract.code' , visible: false},
{ data: 'download', name: 'download', sortable: false, searchable: false },
{ data: 'example_file', name: 'example_file', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'asc' ]],
    pageLength: 10,
  };
  $('.datatable-DepartmentDocument').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection