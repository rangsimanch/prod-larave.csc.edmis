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
            <div class="col col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.tasks.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.task.title_singular') }}
                </a>
                <a class="btn btn-warning" style="float: right;" href="{{ route("admin.tasks.createReport") }}"> Create Report </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="container">
                        <div class="pull-left">
                            {{ trans('cruds.task.title_singular') }} {{ trans('global.list') }}
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Task text-center" id="datatb">
                        <thead>
                            <tr>
                                <th width="10">
    
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.img_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.create_by_user') }}

                                    <select class="form-control filter-select" data-column="2">
                                        <option value=""> Select Employee... </option>
                                        @foreach ($create_by_user as $create_by_user)
                                            <option value="{{ $create_by_user }}"> {{ $create_by_user }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.name') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Activity..." data-column="3"/>
                                </th>
                                
                                <th>
                                    {{ trans('cruds.task.fields.tag') }}

                                    <select class="form-control filter-select" data-column="4">
                                        <option value=""> Select Work Type... </option>
                                        @foreach ($work_type as $work_type)
                                            <option value="{{ $work_type }}"> {{ $work_type }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.location') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Location..." data-column="5"/>
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.due_date') }}

                                    <input type="date" class="form-control filter-input"
                                    placeholder="Search for Activity..." data-column="6"/>
                                </th>
                                <th>
                                    {{ trans('cruds.task.fields.status') }}

                                    <select class="form-control filter-select" data-column="7">
                                        <option value=""> Select Status... </option>
                                        @foreach ($status as $status)
                                            <option value="{{ $status }}"> {{ $status }} </option>
                                        @endforeach
                                    </select>
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
{ data: 'create_by_user_name', name: 'create_by_user.name', sortable: false, },
{ data: 'name', name: 'name', sortable: false, },
{ data: 'tag', name: 'tags.name', sortable: false, },
{ data: 'location', name: 'location', sortable: false, },
{ data: 'due_date', name: 'due_date', },
{ data: 'status_name', name: 'status.name', sortable: false, },
{ data: 'attachment', name: 'attachment', sortable: false, searchable: false },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
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

    // Filter Class
    $('.filter-input').keyup(function(){
        $($.fn.dataTable.tables(true)).DataTable().column( $(this).data('column'))
            .search($(this).val())
            .draw();
        });

        $('.filter-select').change(function(){
            $($.fn.dataTable.tables(true)).DataTable().column( $(this).data('column'))
            .search($(this).val())
            .draw();
        });

        $.fn.dataTable.ext.errMode = 'throw';
        
});

</script>
@endsection