@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.requestForInspection.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.request-for-inspections.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.wbs_level_1') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->wbs_level_1->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.bill') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->bill->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.wbs_level_3') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->wbs_level_3->wbs_level_3_name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.item_1') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->item_1->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.item_2') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->item_2->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.item_3') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->item_3->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.type_of_work') }}
                                    </th>
                                    <td>
                                        {{ App\RequestForInspection::TYPE_OF_WORK_SELECT[$requestForInspection->type_of_work] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.ref_no') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->ref_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.requested_by') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->requested_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.submittal_date') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->submittal_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.contact_person') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->contact_person->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.replied_date') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->replied_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.ipa') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->ipa }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.comment') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->comment }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.files_upload') }}
                                    </th>
                                    <td>
                                        @foreach($requestForInspection->files_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.end_loop') }}
                                    </th>
                                    <td>
                                        {{ $requestForInspection->end_loop }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.requestForInspection.fields.loop_file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($requestForInspection->loop_file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.request-for-inspections.index') }}">
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