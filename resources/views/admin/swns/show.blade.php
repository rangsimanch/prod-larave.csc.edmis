@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.swn.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.swns.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $swn->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $swn->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.dept_code') }}
                                    </th>
                                    <td>
                                        {{ $swn->dept_code->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.submit_date') }}
                                    </th>
                                    <td>
                                        {{ $swn->submit_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $swn->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.reply_ncr') }}
                                    </th>
                                    <td>
                                        {{ App\Swn::REPLY_NCR_SELECT[$swn->reply_ncr] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.ref_doc') }}
                                    </th>
                                    <td>
                                        {!! $swn->ref_doc !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.document_attachment') }}
                                    </th>
                                    <td>
                                        @foreach($swn->document_attachment as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.issue_by') }}
                                    </th>
                                    <td>
                                        {{ $swn->issue_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.responsible') }}
                                    </th>
                                    <td>
                                        {{ $swn->responsible->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.related_specialist') }}
                                    </th>
                                    <td>
                                        {{ $swn->related_specialist->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.review_status') }}
                                    </th>
                                    <td>
                                        {{ App\Swn::REVIEW_STATUS_SELECT[$swn->review_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.construction_specialist') }}
                                    </th>
                                    <td>
                                        {{ $swn->construction_specialist->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.leader') }}
                                    </th>
                                    <td>
                                        {{ $swn->leader->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.auditing_status') }}
                                    </th>
                                    <td>
                                        {{ App\Swn::AUDITING_STATUS_SELECT[$swn->auditing_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.swn.fields.documents_status') }}
                                    </th>
                                    <td>
                                        {{ App\Swn::DOCUMENTS_STATUS_SELECT[$swn->documents_status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.swns.index') }}">
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