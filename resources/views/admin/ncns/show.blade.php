@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.ncn.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.ncns.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $ncn->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $ncn->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.dept_code') }}
                                    </th>
                                    <td>
                                        {{ $ncn->dept_code->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.issue_date') }}
                                    </th>
                                    <td>
                                        {{ $ncn->issue_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.attachment_description') }}
                                    </th>
                                    <td>
                                        {!! $ncn->attachment_description !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.pages_of_attachment') }}
                                    </th>
                                    <td>
                                        {{ $ncn->pages_of_attachment }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.file_attachment') }}
                                    </th>
                                    <td>
                                        @foreach($ncn->file_attachment as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.acceptance_date') }}
                                    </th>
                                    <td>
                                        {{ $ncn->acceptance_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.documents_status') }}
                                    </th>
                                    <td>
                                        {{ App\Ncn::DOCUMENTS_STATUS_SELECT[$ncn->documents_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.issue_by') }}
                                    </th>
                                    <td>
                                        {{ $ncn->issue_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.leader') }}
                                    </th>
                                    <td>
                                        {{ $ncn->leader->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.construction_specialist') }}
                                    </th>
                                    <td>
                                        {{ $ncn->construction_specialist->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncn.fields.related_specialist') }}
                                    </th>
                                    <td>
                                        {{ $ncn->related_specialist->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.ncns.index') }}">
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