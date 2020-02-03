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
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Rfa text-center" id="datatb">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                
                                <th>
                                    {{ trans('cruds.rfa.fields.document_status') }}

                                    <select class="form-control filter-select" data-column="1">
                                        <option value=""> Select Status... </option>
                                        @foreach ($document_status as $status)
                                            <option value="{{ $status }}"> {{ $status }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title_eng') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for title(EN)..." data-column="2"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for title(TH)..." data-column="3"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.title_cn') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for title(CN)..." data-column="4"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.origin_number') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Origin Number..." data-column="5"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.document_number') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Document Number..." data-column="6"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.rfa_code') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for RFA code..." data-column="7"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.type') }}
                                    
                                    <select class="form-control filter-select" data-column="8">
                                        <option value=""> Select Type... </option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type }}"> {{ $type }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.worktype') }}

                                    <select class="form-control filter-select" data-column="9">
                                        <option value=""> Select Work type... </option>
                                        @foreach ($work_types as $work_type)
                                            <option value="{{ $work_type }}"> {{ $work_type }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.construction_contract') }}

                                     <select class="form-control filter-select" data-column="10">
                                        <option value=""> Select Contract... </option>
                                        @foreach ($construction_contracts as $construction_contract)
                                            <option value="{{ $construction_contract }}"> {{ $construction_contract }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.wbs_level_3') }}

                                    <select class="form-control filter-select" data-column="11">
                                        <option value=""> Select WBS Lv3... </option>
                                        @foreach ($wbs_level_3s as $wbs_level_3)
                                            <option value="{{ $wbs_level_3 }}"> {{ $wbs_level_3 }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.wbs_level_4') }}

                                    <select class="form-control filter-select" data-column="12">
                                        <option value=""> Select WBS Lv4... </option>
                                        @foreach ($wbs_level_4s as $wbs_level_4)
                                            <option value="{{ $wbs_level_4 }}"> {{ $wbs_level_4 }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.submit_date') }}

                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.issueby') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Issue by..." data-column="14"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.assign') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Assign to..." data-column="15"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.file_upload_1') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.commercial_file_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.action_by') }}
                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Action by..." data-column="18"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.comment_by') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Comment by..." data-column="19"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.information_by') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Information by..." data-column="20"/>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.receive_date') }}

                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.target_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.note_2') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.comment_status') }}

                                    <select class="form-control filter-select" data-column="24">
                                        <option value=""> Select Status... </option>
                                        @foreach ($comment_statuses as $comment_statuse)
                                            <option value="{{ $comment_statuse }}"> {{ $comment_statuse }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.note_3') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.document_ref') }}

                                    <input type="text" class="form-control filter-input"
                                    placeholder="Search for Information by..." data-column="26"/>
                                </th>
                                
                                <th>
                                    {{ trans('cruds.rfa.fields.document_file_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.for_status') }}
                                   
                                    <select class="form-control filter-select" data-column="28">
                                        <option value=""> Select Status... </option>
                                        @foreach ($for_statuses as $for_statuse)
                                            <option value="{{ $for_statuse }}"> {{ $for_statuse }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.created_at') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.create_by_user') }}
                                </th>
                                <!-- <th>
                                    {{ trans('cruds.rfa.fields.update_by_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.approve_by_user') }}
                                </th> -->
                                <th>
                                    {{ trans('cruds.rfa.fields.updated_at') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.hardcopy_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.team') }}

                                    <select class="form-control filter-select" data-column="33">
                                        <option value=""> Select Type... </option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team }}"> {{ $team }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    Check
                                </th>
                                <th>
                                {{ trans('cruds.rfa.fields.action') }}
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
{ data: 'document_status_status_name', name: 'document_status.status_name' }, // 1
{ data: 'title_eng', name: 'title_eng' }, // 2
{ data: 'title', name: 'title' }, // 3
{ data: 'title_cn', name: 'title_cn' }, // 4
{ data: 'origin_number', name: 'origin_number' }, // 5
{ data: 'document_number', name: 'document_number' }, // 6
{ data: 'rfa_code', name: 'rfa_code' }, // 7
{ data: 'type.type_code', name: 'type.type_code' }, // 8
{ data: 'worktype', name: 'worktype' }, // 9
{ data: 'construction_contract_code', name: 'construction_contract.code' }, // 10
{ data: 'wbs_level_3_wbs_level_3_code', name: 'wbs_level_3.wbs_level_3_code' }, // 11
{ data: 'wbs_level_4_wbs_level_4_code', name: 'wbs_level_4.wbs_level_4_code' }, // 12
{ data: 'submit_date', name: 'submit_date' }, // 13
{ data: 'issueby_name', name: 'issueby.name' }, // 14
{ data: 'assign_name', name: 'assign.name' }, // 15
{ data: 'file_upload_1', name: 'file_upload_1', sortable: false, searchable: false }, // 16
{ data: 'commercial_file_upload', name: 'commercial_file_upload', sortable: false, searchable: false }, // 17
{ data: 'action_by_name', name: 'action_by.name' }, // 18
{ data: 'comment_by_name', name: 'comment_by.name' }, // 19
{ data: 'information_by_name', name: 'information_by.name' }, // 20
{ data: 'receive_date', name: 'receive_date' }, // 21
{ data: 'target_date', name: 'target_date' }, // 22
{ data: 'note_2', name: 'note_2' }, // 23
{ data: 'comment_status_name', name: 'comment_status.name' }, // 24
{ data: 'note_3', name: 'note_3' }, // 25
{ data: 'document_ref', name: 'document_ref' }, // 26
{ data: 'document_file_upload', name: 'document_file_upload', sortable: false, searchable: false }, // 27
{ data: 'for_status_name', name: 'for_status.name' }, // 28
{ data: 'created_at', name: 'created_at' }, // 29
{ data: 'create_by_user_name', name: 'create_by_user.name' }, // 30
// { data: 'update_by_user_name', name: 'update_by_user.name' },
// { data: 'approve_by_user_name', name: 'approve_by_user.name' },
{ data: 'updated_at', name: 'updated_at' }, // 31
{ data: 'hardcopy_date', name: 'hardcopy_date' }, // 32
{ data: 'team_code', name: 'team.code' }, // 33
{ data: 'check_revision', name: 'check_revision', visible: false,}, // 34
{ data: 'actions', name: '{{ trans('global.actions') }}' } // 35
    ],
    order: [[ 29, 'desc' ]],
    pageLength: 10,
  };
  $('.datatable-Rfa').DataTable(dtOverrideGlobals);
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