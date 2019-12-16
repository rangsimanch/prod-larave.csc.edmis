@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.team.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.teams.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.team.fields.id') }}
                        </th>
                        <td>
                            {{ $team->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.team.fields.name') }}
                        </th>
                        <td>
                            {{ $team->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.team.fields.code') }}
                        </th>
                        <td>
                            {{ $team->code }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.teams.index') }}">
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
            <a class="nav-link" href="#team_users" role="tab" data-toggle="tab">
                {{ trans('cruds.user.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#team_tasks" role="tab" data-toggle="tab">
                {{ trans('cruds.task.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#team_rfas" role="tab" data-toggle="tab">
                {{ trans('cruds.rfa.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#team_file_managers" role="tab" data-toggle="tab">
                {{ trans('cruds.fileManager.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="team_users">
            @includeIf('admin.teams.relationships.teamUsers', ['users' => $team->teamUsers])
        </div>
        <div class="tab-pane" role="tabpanel" id="team_tasks">
            @includeIf('admin.teams.relationships.teamTasks', ['tasks' => $team->teamTasks])
        </div>
        <div class="tab-pane" role="tabpanel" id="team_rfas">
            @includeIf('admin.teams.relationships.teamRfas', ['rfas' => $team->teamRfas])
        </div>
        <div class="tab-pane" role="tabpanel" id="team_file_managers">
            @includeIf('admin.teams.relationships.teamFileManagers', ['fileManagers' => $team->teamFileManagers])
        </div>
    </div>
</div>

@endsection