@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.team.title') }}
                </div>
                <div class="panel-body">
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

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.relatedData') }}
                </div>
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    <li role="presentation">
                        <a href="#team_users" aria-controls="team_users" role="tab" data-toggle="tab">
                            {{ trans('cruds.user.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#team_tasks" aria-controls="team_tasks" role="tab" data-toggle="tab">
                            {{ trans('cruds.task.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#team_rfas" aria-controls="team_rfas" role="tab" data-toggle="tab">
                            {{ trans('cruds.rfa.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#team_file_managers" aria-controls="team_file_managers" role="tab" data-toggle="tab">
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

        </div>
    </div>
</div>
@endsection