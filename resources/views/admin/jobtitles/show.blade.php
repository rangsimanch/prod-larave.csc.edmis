@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.jobtitle.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.jobtitles.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.jobtitle.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $jobtitle->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.jobtitle.fields.department') }}
                                    </th>
                                    <td>
                                        @foreach($jobtitle->departments as $key => $department)
                                            <span class="label label-info">{{ $department->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.jobtitles.index') }}">
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
                        <a href="#jobtitle_users" aria-controls="jobtitle_users" role="tab" data-toggle="tab">
                            {{ trans('cruds.user.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="jobtitle_users">
                        @includeIf('admin.jobtitles.relationships.jobtitleUsers', ['users' => $jobtitle->jobtitleUsers])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection