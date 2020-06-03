@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.asBuildPlan.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.as-build-plans.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.asBuildPlan.fields.shop_drawing_title') }}
                                    </th>
                                    <td>
                                        {{ $asBuildPlan->shop_drawing_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.asBuildPlan.fields.drawing_reference') }}
                                    </th>
                                    <td>
                                        @foreach($asBuildPlan->drawing_references as $key => $drawing_reference)
                                            <span class="label label-info">{{ $drawing_reference->coding_of_drawing }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.asBuildPlan.fields.coding_of_shop_drawing') }}
                                    </th>
                                    <td>
                                        {{ $asBuildPlan->coding_of_shop_drawing }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.asBuildPlan.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $asBuildPlan->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.asBuildPlan.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($asBuildPlan->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.asBuildPlan.fields.special_file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($asBuildPlan->special_file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.as-build-plans.index') }}">
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