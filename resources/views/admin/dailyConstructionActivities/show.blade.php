@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.dailyConstructionActivity.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.daily-construction-activities.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyConstructionActivity.fields.work_title') }}
                                    </th>
                                    <td>
                                        {{ $dailyConstructionActivity->work_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyConstructionActivity.fields.operation_date') }}
                                    </th>
                                    <td>
                                        {{ $dailyConstructionActivity->operation_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyConstructionActivity.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $dailyConstructionActivity->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.dailyConstructionActivity.fields.image_upload') }}
                                    </th>
                                    <td>
                                        @foreach($dailyConstructionActivity->image_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                <img src="{{ $media->getUrl('thumb') }}" width="50px" height="50px">
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.daily-construction-activities.index') }}">
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