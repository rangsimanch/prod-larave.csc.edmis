@extends('layouts.admin')

@section('content')
<style>
    table.dataTable tbody tr.row-approved > td { background-color: #d6efd6 !important; }
    table.dataTable tbody tr.row-approve-as-note > td { background-color: #fff4cc !important; }
    table.dataTable tbody tr.row-approved-and-note > td { background-color: #cfe8ff !important; }
    table.dataTable tbody tr.row-edit-comment > td { background-color: #ffe0cc !important; }
    table.dataTable tbody tr.row-not-acceptable > td { background-color: #f8d0d0 !important; }
    table.dataTable tbody tr.row-dash > td { background-color: #f0f0f0 !important; }
</style>
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ $pageTitle }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class="table sticky table-bordered table-striped table-hover ajaxTable datatable datatable-DrawingRfa text-center">
                        <thead>
                            <tr>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.created_at') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.cover_sheet') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.file_upload_1') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.document_status') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.construction_contract') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.boq') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.worktype') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px;"> {{ trans('cruds.rfa.fields.title_eng') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px;"> {{ trans('cruds.rfa.fields.title') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px;"> {{ trans('cruds.rfa.fields.origin_number') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.document_number') }} </p>
                                </th>
                                <th style="padding-left: 10px" style="padding-right: 10px">
                                    <p style="font-size:12px"> {{ trans('cruds.rfatype.fields.type_code') }} </p>
                                </th>
                                <th style="padding-left: 10px" style="padding-right: 10px">
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.wbs_level_3') }} </p>
                                </th>
                                <th style="padding-left: 10px" style="padding-right: 10px">
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.wbs_level_4') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.submit_date') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.comment_status') }} </p>
                                </th>
                                <th>
                                    <p style="font-size:12px"> {{ trans('cruds.rfa.fields.commercial_file_upload') }} </p>
                                </th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <select class="search" style="width:70%">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfa_document_statuses as $key => $item)
                                            <option value="{{ $item->status_name }}">{{ $item->status_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" style="width:70%">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($construction_contracts as $key => $item)
                                            <option value="{{ $item->code }}">{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="search" style="width:70%">
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
                                    <input style="width: 160px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input style="width: 160px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input style="width: 100px" class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
                                    <select class="form-control filter-select select2" data-column="11" style="width:200%">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfatypes as $key => $item)
                                            <option value="{{ $item->type_code }}">{{ $item->type_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control filter-select select2" data-column="12" style="width:200%">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbs_level_threes as $key => $item)
                                            <option value="{{ $item->wbs_level_3_code }}">{{ $item->wbs_level_3_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control filter-select select2" data-column="13" style="width:200%">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($wbslevelfours as $key => $item)
                                            <option value="{{ $item->wbs_level_4_code }}">{{ $item->wbs_level_4_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td></td>
                                <td>
                                    <select class="search">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach($rfa_comment_statuses as $key => $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                        <option value="Approve">Approved & Approve as Note</option>
                                    </select>
                                </td>
                                <td></td>
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
        let dtOverrideGlobals = {
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ $ajaxRoute }}",
            select: false,
            columnDefs: [],
            columns: [
                { data: 'created_at', name: 'created_at', searchable: false },
                { data: 'cover_sheet', name: 'cover_sheet', sortable: false, searchable: false },
                { data: 'file_upload_1', name: 'file_upload_1', sortable: false, searchable: false },
                { data: 'document_status_status_name', name: 'document_status.status_name' },
                { data: 'construction_contract_code', name: 'construction_contract.code', sortable: false },
                { data: 'boq_name', name: 'boq.name' },
                { data: 'worktype', name: 'worktype' },
                { data: 'title_eng', name: 'title_eng', sortable: false },
                { data: 'title', name: 'title', sortable: false },
                { data: 'origin_number', name: 'origin_number', sortable: false },
                { data: 'document_number', name: 'document_number', sortable: false },
                { data: 'type.type_code', name: 'type.type_code' },
                { data: 'wbs_level_3_wbs_level_3_code', name: 'wbs_level_3.wbs_level_3_code' },
                { data: 'wbs_level_4_wbs_level_4_code', name: 'wbs_level_4.wbs_level_4_code' },
                { data: 'submit_date', name: 'submit_date', searchable: false },
                { data: 'comment_status_name', name: 'comment_status.name' },
                { data: 'commercial_file_upload', name: 'commercial_file_upload', sortable: false, searchable: false },
            ],
            createdRow: function (row, data, dataIndex) {
                let status = (data.comment_status_name || '').trim();
                let colorClass = '';
                switch (status) {
                    case 'Approved':
                        colorClass = 'row-approved';
                        break;
                    case 'Approve as Note':
                        colorClass = 'row-approve-as-note';
                        break;
                    case 'Approved & Approve as Note':
                        colorClass = 'row-approved-and-note';
                        break;
                    case 'Edit - Comment':
                        colorClass = 'row-edit-comment';
                        break;
                    case 'Not Acceptable':
                        colorClass = 'row-not-acceptable';
                        break;
                    case '-':
                        colorClass = 'row-dash';
                        break;
                }
                if (colorClass) {
                    $(row).addClass(colorClass);
                }
            },
            orderCellsTop: true,
            order: [[ 0, 'desc' ]],
            pageLength: 10,
            aLengthMenu: [
                [5, 10, 25, 50, 100, 200, 1000, 2000],
                [5, 10, 25, 50, 100, 200, 1000, 2000]
            ],
        };

        let table = $('.datatable-DrawingRfa').DataTable(dtOverrideGlobals);

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

        table.on('column-visibility.dt', function (e, settings, column, state) {
            visibleColumnsIndexes = []
            table.columns(":visible").every(function (colIdx) {
                visibleColumnsIndexes.push(colIdx);
            });
        });

        $('.filter-select').change(function () {
            $($.fn.dataTable.tables(true)).DataTable().column($(this).data('column'))
                .search($(this).val())
                .draw();
        });

        $.fn.dataTable.ext.errMode = 'throw';
    });
</script>
@endsection
