@extends('layouts.admin')
@section('content')
<div class="content">
    @can('complaint_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.complaints.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.complaint.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'Complaint', 'route' => 'admin.complaints.parseCsvImport'])
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.complaint.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table id="datatableComplaint" class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Complaint">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.document_number') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.complaint_recipient') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.received_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.source_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.file_attachment_create') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.complainant') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.complainant_tel') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.complainant_detail') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.complaint_description') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.type_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.impact_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.operator') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.action_detail') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.progress_file') }}
                                </th>
                                <th>
                                    {{ trans('cruds.complaint.fields.action_date') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\Complaint::STATUS_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
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
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
                                    <input type="text" name="daterange" id="daterange" class="form-control daterange" value="" autocomplete="off" placeholder="Select Period..">
                                    <!-- <input id="reportrange" type="text" name="reportrange" value="" autocomplete="off" placeholder="Select Period.."/> -->
                                    <!-- <input type="date" class="form-control filter-input" data-column="5"/> -->
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\Complaint::SOURCE_CODE_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
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
                                        @foreach(App\Complaint::TYPE_CODE_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\Complaint::IMPACT_CODE_SELECT as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
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
        @can('complaint_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.complaints.massDestroy') }}",
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
            responsive: true,
            colReorder: true,
            aaSorting: [],
            ajax: "{{ route('admin.complaints.index') }}",
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'status', name: 'status' },
                { data: 'construction_contract_code', name: 'construction_contract.code' },
                { data: 'document_number', name: 'document_number' },
                { data: 'complaint_recipient_code', name: 'complaint_recipient.code' },
                { data: 'received_date', name: 'received_date'},
                { data: 'source_code', name: 'source_code' },
                { data: 'file_attachment_create', name: 'file_attachment_create', sortable: false, searchable: false },
                { data: 'complainant', name: 'complainant' },
                { data: 'complainant_tel', name: 'complainant_tel' },
                { data: 'complainant_detail', name: 'complainant_detail' },
                { data: 'complaint_description', name: 'complaint_description' },
                { data: 'type_code', name: 'type_code' },
                { data: 'impact_code', name: 'impact_code' },
                { data: 'operator_name', name: 'operator.name' },
                { data: 'action_detail', name: 'action_detail' },
                { data: 'progress_file', name: 'progress_file', sortable: false, searchable: false },
                { data: 'action_date', name: 'action_date' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 5, 'desc' ]],
            pageLength: 10,
            processing: true,
        };
        var table = $('.datatable-Complaint').DataTable(dtOverrideGlobals);
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

        $(".daterange").on('onclick', function (ev, picker) {
            ev.preventDefault();
            table.column(dataIdx)
                .search('')
                .draw();
        });
    }); 

</script>


@endsection