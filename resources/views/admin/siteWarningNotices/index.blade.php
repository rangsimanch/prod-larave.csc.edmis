@extends('layouts.admin')
@section('content')
<div class="content">
    @can('site_warning_notice_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.site-warning-notices.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.siteWarningNotice.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.siteWarningNotice.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SiteWarningNotice">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.subject') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.location') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.reply_by_ncr') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.swn_no') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.submit_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.to_team') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.construction_contract') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.description') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.issue_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.reviewed_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.attachment') }}
                                </th>
                                

                                
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.root_cause') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.containment_responsible') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.containment_completion_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.corrective_responsible') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.corrective_completion_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.section_2_reviewed_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.section_2_approved_by') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.review_and_judgement_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.note') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.csc_issuer') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.csc_qa') }}
                                </th>



                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.disposition_status') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.csc_pm') }}
                                </th>
                                <th>
                                    {{ trans('cruds.siteWarningNotice.fields.file_upload') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
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
                                        @foreach(App\SiteWarningNotice::REPLY_BY_NCR_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                </td>
                                <td>
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
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SiteWarningNotice::REVIEW_AND_JUDGEMENT_STATUS_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
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
                                    <select class="search" strict="true">
                                        <option value>{{ trans('global.all') }}</option>
                                        @foreach(App\SiteWarningNotice::DISPOSITION_STATUS_SELECT as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
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
@can('site_warning_notice_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.site-warning-notices.massDestroy') }}",
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
    ajax: "{{ route('admin.site-warning-notices.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'subject', name: 'subject' },
{ data: 'location', name: 'location' },
{ data: 'reply_by_ncr', name: 'reply_by_ncr' },
{ data: 'swn_no', name: 'swn_no' },
{ data: 'submit_date', name: 'submit_date' },
{ data: 'to_team_code', name: 'to_team.code' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'description', name: 'description' },
{ data: 'issue_by_name', name: 'issue_by.name' },
{ data: 'reviewed_by_name', name: 'reviewed_by.name' },
{ data: 'attachment', name: 'attachment', sortable: false, searchable: false },
{ data: 'root_cause', name: 'root_cause' },
{ data: 'containment_responsible_name', name: 'containment_responsible.name' },
{ data: 'containment_completion_date', name: 'containment_completion_date' },
{ data: 'corrective_responsible_name', name: 'corrective_responsible.name' },
{ data: 'corrective_completion_date', name: 'corrective_completion_date' },
{ data: 'section_2_reviewed_by_name', name: 'section_2_reviewed_by.name' },
{ data: 'section_2_approved_by_name', name: 'section_2_approved_by.name' },
{ data: 'review_and_judgement_status', name: 'review_and_judgement_status' },
{ data: 'note', name: 'note' },
{ data: 'csc_issuer_name', name: 'csc_issuer.name' },
{ data: 'csc_qa_name', name: 'csc_qa.name' },
{ data: 'disposition_status', name: 'disposition_status' },
{ data: 'csc_pm_name', name: 'csc_pm.name' },
{ data: 'file_upload', name: 'file_upload', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 5, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-SiteWarningNotice').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  $('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value
      table
        .column($(this).parent().index())
        .search(value, strict)
        .draw()
  });
});

</script>
@endsection