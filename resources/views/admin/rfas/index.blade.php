@extends('layouts.admin')
@section('content')
<div class="content">
    @can('rfa_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.rfas.create') }}">
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
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Rfa text-center">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    Action
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.submit_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.cover_sheet') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.file_upload_1') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.document_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.construction_contract') }}
                                </th>
                                
                                <th>
                                    {{ trans('cruds.rfa.fields.boq') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.worktype') }}
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
                                    {{ trans('cruds.rfa.fields.origin_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfatype.fields.type_code') }}
                                </th>
                            
                                <th>
                                    {{ trans('cruds.rfa.fields.wbs_level_3') }}
                                </th>
                               
                                <th>
                                    {{ trans('cruds.rfa.fields.wbs_level_4') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.rfa_code') }}
                                </th>
                                
                                <th>
                                    {{ trans('cruds.rfa.fields.review_time') }}
                                </th>   
                                <th>
                                    {{ trans('cruds.rfa.fields.issueby') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.assign') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.action_by') }}
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
                                    {{ trans('cruds.rfa.fields.comment_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.create_by_user') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.incoming_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.outgoing_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.distribute_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.process_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.outgoing_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.final_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.commercial_file_upload') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.distribute_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.reviewed_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.submittals_file') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.updated_at') }}
                                </th>
                                <th>
                                    {{ trans('cruds.rfa.fields.created_at') }}
                                </th>
                               
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <input type="text" name="daterange" id="daterange" class="form-control daterange" value="" autocomplete="off" placeholder="Select Period..">
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfa_document_statuses as $key => $item)
                                            <option value="{{ $item->status_name }}">{{ $item->status_name }}</option>
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($bo_qs as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\Rfa::WORKTYPE_SELECT as $key => $item)
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfatypes as $key => $item)
                                            <option value="{{ $item->type_code }}">{{ $item->type_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbs_level_threes as $key => $item)
                                            <option value="{{ $item->wbs_level_3_code }}">{{ $item->wbs_level_3_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbslevelfours as $key => $item)
                                            <option value="{{ $item->wbs_level_4_code }}">{{ $item->wbs_level_4_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfa_comment_statuses as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
                                        @foreach($users as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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
{ data: 'actions', name: '{{ trans('global.actions') }}' ,searchable: false},
{ data: 'submit_date', name: 'submit_date' },
{ data: 'cover_sheet', name: 'cover_sheet' ,sortable: false, searchable: false},
{ data: 'file_upload_1', name: 'file_upload_1', sortable: false, searchable: false},
{ data: 'document_status_status_name', name: 'document_status.status_name'},
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'boq_name', name: 'boq.name' },
{ data: 'worktype', name: 'worktype' },
{ data: 'title_eng', name: 'title_eng' },
{ data: 'title', name: 'title' },
{ data: 'title_cn', name: 'title_cn' , visible: false},
{ data: 'origin_number', name: 'origin_number' },
{ data: 'document_number', name: 'document_number' },
{ data: 'type.type_code', name: 'type.type_code' },
{ data: 'wbs_level_3_wbs_level_3_code', name: 'wbs_level_3.wbs_level_3_code' },
{ data: 'wbs_level_4_wbs_level_4_code', name: 'wbs_level_4.wbs_level_4_code' },
{ data: 'rfa_code', name: 'rfa_code' ,visible: false},
{ data: 'review_time', name: 'review_time' ,visible: false},
{ data: 'issueby_name', name: 'issueby.name' ,visible: false},
{ data: 'assign_name', name: 'assign.name' ,visible: false},
{ data: 'action_by_name', name: 'action_by.name' ,visible: false},
{ data: 'comment_by_name', name: 'comment_by.name' ,visible: false},
{ data: 'information_by_name', name: 'information_by.name' ,visible: false},
{ data: 'receive_date', name: 'receive_date' ,visible: false},
{ data: 'comment_status_name', name: 'comment_status.name' },
{ data: 'create_by_user_name', name: 'create_by_user.name' ,visible: false},
{ data: 'incoming_number', name: 'incoming_number' ,visible: false},
{ data: 'outgoing_number', name: 'outgoing_number' ,visible: false},
{ data: 'distribute_date', name: 'distribute_date' ,visible: false},
{ data: 'process_date', name: 'process_date' ,visible: false},
{ data: 'outgoing_date', name: 'outgoing_date' ,visible: false},
{ data: 'final_date', name: 'final_date' ,visible: false},
{ data: 'commercial_file_upload', name: 'commercial_file_upload', sortable: false, searchable: false },
{ data: 'distribute_by_name', name: 'distribute_by.name' ,visible: false},
{ data: 'reviewed_by_name', name: 'reviewed_by.name' ,visible: false},
{ data: 'submittals_file', name: 'submittals_file', sortable: false, searchable: false ,visible: false},
{ data: 'updated_at', name: 'updated_at' , visible: false},
{ data: 'created_at', name: 'created_at' },

    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-Rfa').DataTable(dtOverrideGlobals);
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
  // date range
  $('.daterange').daterangepicker({
        ranges: {
            "Today": [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 last days': [moment().subtract(6, 'days'), moment()],
            '30 last days': [moment().subtract(29, 'days'), moment()],
            'This month': [moment().startOf('month'), moment().endOf('month')],
            'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
        ,
        autoUpdateInput: false,
        opens: "left",
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY'
        }
    });

    let startDate;
    let endDate;
    let dataIdx = 5;  //current data column to work with

    // Function for converting a dd/mmm/yyyy date value into a numeric string for comparison (example 01-Dec-2010 becomes 20101201
    function parseDateValue(rawDate) {
        var d = moment(rawDate, "DD/MM/YYYY").format("DD/MM/YYYY");
        var dateArray = d.split("/");
        var parsedDate = dateArray[2] + dateArray[1] + dateArray[0];
        return parsedDate;
    }

    function covertDateValue(text) {
        let year = text.slice(0, 4);
        let month = text.slice(4, 6);
        let day = text.slice(6, 8);
        var parsedDate = year + "-" + month + "-" + day;
        return parsedDate;
    }

    //filter on daterange
    $(".daterange").on('apply.daterangepicker', function (ev, picker) {
        ev.preventDefault();
        //if blank date option was selected
        if ((picker.startDate.format('DD/MM/YYYY') == "01/01/0001") && (picker.endDate.format('DD/MM/YYYY')) == "01/01/0001") {
            $(this).val('');
            val = "^$";
            table.column(dataIdx)
               .search(val, true, false)
               .draw();
        }
        else {
            //set field value
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            //run date filter
            startDate = picker.startDate.format('DD/MM/YYYY');
            endDate = picker.endDate.format('DD/MM/YYYY');

            var dateStart = parseDateValue(startDate);
            var dateEnd = parseDateValue(endDate);
            
            var filteredData = table
                    .column(dataIdx)
                    .data()
                    .filter(function (value, index) {
                        var evalDate = value === "" ? 0 : parseDateValue(value);
                        if ((isNaN(dateStart) && isNaN(dateEnd)) || (evalDate >= dateStart && evalDate <= dateEnd)) {
                            return true;
                        }
                        return false;
                    });
            var val = "";
            for (var count = 0; count < filteredData.length; count++) {
                var filterDate = new Date(covertDateValue(parseDateValue(filteredData[count])));
                let searchData = filterDate.getFullYear() + "-" + ("0" + (filterDate.getMonth() + 1)).slice(-2) + "-" + ("0" + filterDate.getDate()).slice(-2);
                val += searchData + "|";
            }
            val = val.slice(0, -1);
            table.column(dataIdx)
                .search(val ? "^" + val + "$" : "^" + "-" + "$", true, false)
                .draw();
            
            console.log(filteredData.length);
            console.log(val ? "^" + val + "$" : "^" + "-" + "$");
            }
        });

        $(".daterange").on('cancel.daterangepicker', function (ev, picker) {
            ev.preventDefault();
            $(this).val('');
            table.column(dataIdx)
                .search('')
                .draw();
        });

        $(".daterange").on('show.daterangepicker', function (ev, picker) {
            ev.preventDefault();
            table.column(dataIdx)
                .search('')
                .draw();
        });
    });

</script>
@endsection