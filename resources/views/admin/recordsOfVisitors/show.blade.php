@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.recordsOfVisitor.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.records-of-visitors.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recordsOfVisitor.fields.date_of_visit') }}
                                    </th>
                                    <td>
                                        {{ $recordsOfVisitor->date_of_visit }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recordsOfVisitor.fields.name_of_visitor') }}
                                    </th>
                                    <td>
                                        {{ $recordsOfVisitor->name_of_visitor }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recordsOfVisitor.fields.details') }}
                                    </th>
                                    <td>
                                        {{ $recordsOfVisitor->details }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.recordsOfVisitor.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($recordsOfVisitor->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.records-of-visitors.index') }}">
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