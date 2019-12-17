@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.constructionContract.title') }}
    </div>

    <div class="card-body">
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

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#construction_contract_rfas" role="tab" data-toggle="tab">
                {{ trans('cruds.rfa.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#construction_contract_users" role="tab" data-toggle="tab">
                {{ trans('cruds.user.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#construction_contract_tasks" role="tab" data-toggle="tab">
                {{ trans('cruds.task.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#construction_contract_file_managers" role="tab" data-toggle="tab">
                {{ trans('cruds.fileManager.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="construction_contract_rfas">
            @includeIf('admin.constructionContracts.relationships.constructionContractRfas', ['rfas' => $constructionContract->constructionContractRfas])
        </div>
        <div class="tab-pane" role="tabpanel" id="construction_contract_users">
            @includeIf('admin.constructionContracts.relationships.constructionContractUsers', ['users' => $constructionContract->constructionContractUsers])
        </div>
        <div class="tab-pane" role="tabpanel" id="construction_contract_tasks">
            @includeIf('admin.constructionContracts.relationships.constructionContractTasks', ['tasks' => $constructionContract->constructionContractTasks])
        </div>
        <div class="tab-pane" role="tabpanel" id="construction_contract_file_managers">
            @includeIf('admin.constructionContracts.relationships.constructionContractFileManagers', ['fileManagers' => $constructionContract->constructionContractFileManagers])
        </div>
    </div>
</div>

@endsection