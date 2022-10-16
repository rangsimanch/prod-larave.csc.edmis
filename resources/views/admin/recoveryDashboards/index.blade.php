@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.recoveryDashboard.title') }}
                </div>
                <div class="panel-body">
                    <p>
                        <p hidden> {{ date_default_timezone_set("Asia/Bangkok") }} </p>
                        <h5 style="color: gray"> Last updated at {{ date("Y-m-d h:i:sa") }} </h5>
                        <h2>All File Summary Recovered:  
                            {{ $count_all_format }} / {{ $all_sum }}
                            <br>
                        </h2>
                        <h3>  PDF Summary Recovered : 
                            {{ $count_pdf_format }} / {{ $pdf_sum }}
                            <br>
                        </h3>
                        <h4>    ↳ Model RFA : 
                            {{ $count_rfa_format }} / {{ $rfa_sum }}
                            <br>
                        </h4>
                        <h4>    ↳ Model SRT : 
                            {{ $count_srt_format }} / {{ $srt_sum }}
                            <br>
                        </h4>
                        <h4>    ↳ Other(RFI, RFN, SWN, NCN, NCR, Letter, etc.) : 
                            {{ $count_other_format }} / {{ $other_sum }}
                            <br>
                        </h4>
                    </p>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    RFA Files List
                                </div>
                                <div class="panel-body">
                                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-rfa-recovery text-center">
                                        <thead>
                                            <tr>
                                                <th width="10">

                                                </th>
                                                <th>
                                                    ID
                                                </th>
                                                <th>
                                                    Title
                                                </th>
                                                <th>
                                                    Document Number
                                                </th>
                                                <th>
                                                    Originator Number
                                                </th>
                                                <th>
                                                    Construction Contract
                                                </th>
                                                <th>
                                                    Upload Field
                                                </th>
                                                <th>
                                                    Created at
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
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
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

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
        let dtOverrideGlobals = {
        buttons: dtButtons,
        processing: true,
        deferRender: true,
        retrieve: true,
        aaSorting: [],
        ajax: "{{ route('admin.recovery-dashboards.index') }}",
        columns: [
            { data: 'placeholder', name: 'placeholder' },
            { data: 'id', name: 'id'},
            { data: 'title', name: 'title' },
            { data: 'document_number', name: 'document_number' },
            { data: 'origin_number', name: 'origin_number' },
            { data: 'code', name: 'code' },
            { data: 'collection_name', name: 'collection_name' },
            { data: 'created_at', name: 'created_at' },
        ],
        orderCellsTop: true,
        order: [[ 1, 'desc' ]],
        pageLength: 10,
        lengthMenu: [[ 10, 25, 100, -1], [10, 25, 100, "All"]],
        };
        let table = $('.datatable-rfa-recovery').DataTable(dtOverrideGlobals);
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
