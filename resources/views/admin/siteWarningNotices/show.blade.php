@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.siteWarningNotice.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.site-warning-notices.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.reply_by_ncr') }}
                                    </th>
                                    <td>
                                        {{ App\SiteWarningNotice::REPLY_BY_NCR_SELECT[$siteWarningNotice->reply_by_ncr] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.swn_no') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->swn_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.submit_date') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->submit_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.to_team') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->to_team->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.issue_by') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->issue_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.reviewed_by') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->reviewed_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.attachment') }}
                                    </th>
                                    <td>
                                        @foreach($siteWarningNotice->attachment as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.root_cause') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->root_cause }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.containment_actions') }}
                                    </th>
                                    <td>
                                        {!! $siteWarningNotice->containment_actions !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.containment_responsible') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->containment_responsible->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.containment_completion_date') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->containment_completion_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.corrective_and_preventive') }}
                                    </th>
                                    <td>
                                        {!! $siteWarningNotice->corrective_and_preventive !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.corrective_responsible') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->corrective_responsible->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.corrective_completion_date') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->corrective_completion_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.section_2_reviewed_by') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->section_2_reviewed_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.section_2_approved_by') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->section_2_approved_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.review_and_judgement_status') }}
                                    </th>
                                    <td>
                                        {{ App\SiteWarningNotice::REVIEW_AND_JUDGEMENT_STATUS_SELECT[$siteWarningNotice->review_and_judgement_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.csc_issuer') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->csc_issuer->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.csc_qa') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->csc_qa->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.disposition_status') }}
                                    </th>
                                    <td>
                                        {{ App\SiteWarningNotice::DISPOSITION_STATUS_SELECT[$siteWarningNotice->disposition_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.siteWarningNotice.fields.csc_pm') }}
                                    </th>
                                    <td>
                                        {{ $siteWarningNotice->csc_pm->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.site-warning-notices.index') }}">
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