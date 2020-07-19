@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.nonConformanceNotice.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.non-conformance-notices.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceNotice->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceNotice->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.ref_no') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceNotice->ref_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.submit_date') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceNotice->submit_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.attachment') }}
                                    </th>
                                    <td>
                                        @foreach($nonConformanceNotice->attachment as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.attachment_description') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceNotice->attachment_description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.status_ncn') }}
                                    </th>
                                    <td>
                                        {{ App\NonConformanceNotice::STATUS_NCN_SELECT[$nonConformanceNotice->status_ncn] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.csc_issuers') }}
                                    </th>
                                    <td>
                                        {{ $nonConformanceNotice->csc_issuers->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($nonConformanceNotice->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.cc_srt') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $nonConformanceNotice->cc_srt ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.cc_pmc') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $nonConformanceNotice->cc_pmc ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.cc_cec') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $nonConformanceNotice->cc_cec ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.nonConformanceNotice.fields.cc_csc') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $nonConformanceNotice->cc_csc ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.non-conformance-notices.index') }}">
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