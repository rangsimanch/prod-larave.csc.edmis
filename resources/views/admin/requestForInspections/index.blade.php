@extends('layouts.admin')
@section('content')

@if (session('alert'))
    <div class="alert alert-success">
        {{ session('alert') }}
    </div>
@endif

<div class="content">
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            @can('request_for_inspection_create')
                <a class="btn btn-success" href="{{ route('admin.request-for-inspections.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.requestForInspection.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'RequestForInspection', 'route' => 'admin.request-for-inspections.parseCsvImport'])

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fileuploadCCSP">
                    Upload matching PDF Files
                </button>
            @endcan
            
            
            <button type="button" class="btn btn-primary" id="bulkDownloadBtn" disabled>
                <i class="fa fa-download"></i> {{ trans('global.downloadSelected') }}
            </button>
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.requestForInspection.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-RequestForInspection text-center">
                        <thead>
                            <tr>
                                <th width="10">
                                    <input type="checkbox" id="select-all" class="form-check-input" style="margin-left: 0;">
                                </th>
                                <th>
                                    Action
                                </th>
                                
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.created_at') }}
                                </th>

                                <th>
                                    {{ trans('cruds.requestForInspection.fields.subject') }}
                                </th>

                                <th>
                                    {{ trans('cruds.requestForInspection.fields.bill') }}
                                </th>

                                <th>
                                    {{ trans('cruds.requestForInspection.fields.construction_contract') }}
                                </th>
                                  
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.ref_no') }}
                                </th>
                               
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.submittal_date') }}
                                </th>
                              
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.ipa') }}
                                </th>
                                <th>
                                    {{ trans('cruds.requestForInspection.fields.files_upload') }}
                                </th>
                                
                               
                            </tr>
                            <tr>
                                <td>
                                </td>

                                <td>
                                </td> 

                                <td>
                                </td>

                                <td>
                                    <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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


{{-- Upload Summary Modal --}}
<div class="modal fade" id="uploadSummaryModal" tabindex="-1" role="dialog" aria-labelledby="uploadSummaryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="uploadSummaryLabel">ผลลัพธ์การอัปโหลด</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if(session()->has('upload_summary'))
          @php
            $summary = session('upload_summary');
          @endphp

          @if(!empty($summary['success']))
            <h4 class="text-success">Mapping สำเร็จ</h4>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th scope="col">Subject</th>
                    <th scope="col">RFN No.</th>
                    <th scope="col">File</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($summary['success'] as $row)
                    <tr>
                      <td>{{ $row['subject'] ?? '-' }}</td>
                      <td>{{ $row['ref_no'] ?? '-' }}</td>
                      <td>{{ $row['file'] ?? '-' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
          @if(isset($summary) && !empty($summary['failed']))
            <h4 class="text-danger" style="margin-top:20px;">Mapping ไม่สำเร็จ</h4>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th scope="col">เหตุผล</th>
                    <th scope="col">RFN No.</th>
                    <th scope="col">File</th>
                    <th scope="col">Subject</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($summary['failed'] as $row)
                    <tr>
                      <td>{{ $row['reason'] ?? '-' }}</td>
                      <td>{{ $row['ref_no'] ?? '-' }}</td>
                      <td>{{ $row['file'] ?? '-' }}</td>
                      <td>{{ $row['subject'] ?? '-' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
 </div>

        </div>
    </div>
</div>


<div class="modal fade" id="fileuploadCCSP" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Upload matching PDF Files</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route("admin.rfns.ModalAttachFilesUpload") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="construction_contract_id">Construction Contract</label>
                <select class="form-control" name="construction_contract_id" id="construction_contract_id" required oninvalid="this.setCustomValidity('กรุณาเลือกสัญญา')" oninput="this.setCustomValidity('')">
                    <option value="">{{ trans('global.pleaseSelect') }}</option>
                    @php
                        use Illuminate\Support\Facades\Auth;
                        $ccOptions = Auth::id() != 1
                            ? \App\ConstructionContract::where('id', session('construction_contract_id'))->pluck('code','id')
                            : \App\ConstructionContract::all()->pluck('code','id');
                    @endphp
                    @foreach($ccOptions as $id => $code)
                        <option value="{{ $id }}">{{ $code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group {{ $errors->has('files_upload') ? 'has-error' : '' }}">
                <label for="files_upload">{{ trans('cruds.requestForInspection.fields.files_upload') }}</label>
                <div class="needsclick dropzone" id="files_upload-dropzone">
                </div>
                @if($errors->has('files_upload'))
                    <span class="help-block" role="alert">{{ $errors->first('files_upload') }}</span>
                @endif
                <span class="help-block">อัปโหลดได้สูงสุด 50 ไฟล์ | ประเภท PDF เท่านั้น | ขนาดไม่เกินไฟล์ละ 5GB | ระบบจะจับคู่ชื่อไฟล์กับ RFN No.</span>
            </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

@endsection
@section('scripts')
@parent

<script>
    var uploadedFilesUploadMap = {}
Dropzone.options.filesUploadDropzone = {
    url: '{{ route('admin.request-for-inspections.storeMedia') }}',
    maxFilesize: 5120, // MB (5GB)
    maxFiles: 50,
    acceptedFiles: 'application/pdf',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5120
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="files_upload[]" value="' + response.name + '">')
      uploadedFilesUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFilesUploadMap[file.name]
      }
      $('form').find('input[name="files_upload[]"][value="' + name + '"]').remove()
    },
    error: function (file, response) {
         var message = $.type(response) === 'string' ? response : (response.errors ? response.errors.file : 'Upload error')
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }
         return _results
     }
}
</script>

<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  // Bulk download button handler
  $('#bulkDownloadBtn').on('click', function() {
      let rows = table.rows({ selected: true }).data();
      if (rows.length === 0) {
          alert('{{ trans('global.datatables.zero_selected') }}');
          return;
      }
      
      let ids = [];
      rows.each(function(row) {
          ids.push(row.id);
      });
      
      // Show loading state
      $(this).html('<i class="fa fa-spinner fa-spin"></i> {{ trans('global.downloading') }}...').prop('disabled', true);
      
      // Submit form to download endpoint
      let form = $('<form>', {
          'method': 'POST',
          'action': '{{ route('admin.request-for-inspections.bulk-download') }}',
          'target': '_blank'
      });
      
      // Add CSRF token and IDs
      form.append($('<input>', {
          'type': 'hidden',
          'name': '_token',
          'value': '{{ csrf_token() }}'
      }));
      
      $.each(ids, function(index, id) {
          form.append($('<input>', {
              'type': 'hidden',
              'name': 'ids[]',
              'value': id
          }));
      });
      
      $('body').append(form);
      form.submit().remove();
      
      // Reset button state after a short delay
      setTimeout(() => {
          $('#bulkDownloadBtn').html('<i class="fa fa-download"></i> {{ trans('global.downloadSelected') }}').prop('disabled', false);
      }, 2000);
  });
  
  @can('request_for_inspection_delete')
    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
    let deleteButton = {
      text: deleteButtonTrans,
      url: "{{ route('admin.request-for-inspections.massDestroy') }}",
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
    ajax: "{{ route('admin.request-for-inspections.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'actions', name: '{{ trans('global.actions') }}' },
{ data: 'created_at', name: 'created_at' },
{ data: 'subject', name: 'subject' },
{ data: 'bill_name', name: 'bill.name' },
{ data: 'construction_contract_code', name: 'construction_contract.code' },
{ data: 'ref_no', name: 'ref_no' },
{ data: 'submittal_date', name: 'submittal_date' },
{ data: 'ipa', name: 'ipa' },
{ data: 'files_upload', name: 'files_upload', sortable: false, searchable: false },
// { data: 'end_loop', name: 'end_loop' },
// { data: 'loop_file_upload', name: 'loop_file_upload', sortable: false, searchable: false },
    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 10,
    aLengthMenu: [
        [5, 10, 25, 50, 100, 200, 1000],
        [5, 10, 25, 50, 100, 200, 1000]
    ],
  };
  let table = $('.datatable-RequestForInspection').DataTable(dtOverrideGlobals);
  
  // Update bulk download button state based on selection
  table.on('select deselect', function(e, dt, type, indexes) {
      let selectedRows = table.rows({ selected: true }).count();
      let totalRows = table.rows().count();
      
      // Update bulk download button state
      $('#bulkDownloadBtn').prop('disabled', selectedRows === 0);
      
      // Update select all checkbox
      $('#select-all').prop('checked', selectedRows === totalRows && totalRows > 0);
      $('#select-all').prop('indeterminate', selectedRows > 0 && selectedRows < totalRows);
  });
  
  // Handle select all checkbox
  $('#select-all').on('change', function() {
      if ($(this).is(':checked')) {
          table.rows().select();
      } else {
          table.rows().deselect();
      }
  });
  
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
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
  
  // Show upload summary modal if present
  @if(session('upload_summary'))
    $('#uploadSummaryModal').modal('show');
  @endif
});

</script>
@endsection