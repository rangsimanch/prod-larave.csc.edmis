@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.constructionContract.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.construction-contracts.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.code') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.document_code') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->document_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_start_1') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->dk_start_1 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_end_1') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->dk_end_1 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_start_2') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->dk_start_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_end_2') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->dk_end_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_start_3') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->dk_start_3 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.dk_end_3') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->dk_end_3 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.roadway_km') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->roadway_km }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.tollway_km') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->tollway_km }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.total_distance_km') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->total_distance_km }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.budget') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->budget }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.constructionContract.fields.works_code') }}
                                    </th>
                                    <td>
                                        {{ $constructionContract->works_code->code ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.construction-contracts.index') }}">
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