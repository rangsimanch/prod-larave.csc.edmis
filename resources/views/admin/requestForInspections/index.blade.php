@extends('layouts.admin')
@section('content')

@if (session('alert'))
    <div class="alert alert-success">
        {{ session('alert') }}
    </div>
@endif

<div class="content">
    @can('request_for_inspection_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.request-for-inspections.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.requestForInspection.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'RequestForInspection', 'route' => 'admin.request-for-inspections.parseCsvImport'])

                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fileuploadCCSP">
                    {{ trans('cruds.requestForInspection.fields.modal_file_upload') }}
                </button> -->

            </div>
        </div>
    @endcan
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



        </div>
    </div>
</div>


<div class="modal fade" id="fileuploadCCSP" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">RFN Files Upload</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route("admin.rfns.ModalAttachFilesUpload") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('files_upload') ? 'has-error' : '' }}">
                <label for="files_upload">{{ trans('cruds.requestForInspection.fields.files_upload') }}</label>
                <div class="needsclick dropzone" id="files_upload-dropzone">
                </div>
                @if($errors->has('files_upload'))
                    <span class="help-block" role="alert">{{ $errors->first('files_upload') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.requestForInspection.fields.files_upload_helper') }}</span>
            </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="sumit" class="btn btn-primary">Save changes</button>
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
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 100
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="files_upload[]" value="' + response.name + '">')
      uploadedFilesUploadMap[file.name] = response.name
      console.log(response.name);
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
    init: function () {
@if(isset($requestForInspection) && $requestForInspection->files_upload)
          var files =
            {!! json_encode($requestForInspection->files_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="files_upload[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
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
@can('request_for_inspection_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
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
});

</script>
@endsection