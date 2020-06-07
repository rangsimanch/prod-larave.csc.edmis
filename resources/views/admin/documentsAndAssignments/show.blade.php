@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.documentsAndAssignment.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.documents-and-assignments.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.documentsAndAssignment.fields.file_name') }}
                                    </th>
                                    <td>
                                        {{ $documentsAndAssignment->file_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.documentsAndAssignment.fields.original_no') }}
                                    </th>
                                    <td>
                                        {{ $documentsAndAssignment->original_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.documentsAndAssignment.fields.receipt_no') }}
                                    </th>
                                    <td>
                                        {{ $documentsAndAssignment->receipt_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.documentsAndAssignment.fields.date_of_receipt') }}
                                    </th>
                                    <td>
                                        {{ $documentsAndAssignment->date_of_receipt }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.documentsAndAssignment.fields.received_from') }}
                                    </th>
                                    <td>
                                        {{ $documentsAndAssignment->received_from }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.documentsAndAssignment.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $documentsAndAssignment->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.documentsAndAssignment.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($documentsAndAssignment->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.documents-and-assignments.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection