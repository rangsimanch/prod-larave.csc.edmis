@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.rfaCommentStatus.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rfa-comment-statuses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.rfaCommentStatus.fields.name') }}
                        </th>
                        <td>
                            {{ $rfaCommentStatus->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rfa-comment-statuses.index') }}">
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
            <a class="nav-link" href="#comment_status_rfas" role="tab" data-toggle="tab">
                {{ trans('cruds.rfa.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#for_status_rfas" role="tab" data-toggle="tab">
                {{ trans('cruds.rfa.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="comment_status_rfas">
            @includeIf('admin.rfaCommentStatuses.relationships.commentStatusRfas', ['rfas' => $rfaCommentStatus->commentStatusRfas])
        </div>
        <div class="tab-pane" role="tabpanel" id="for_status_rfas">
            @includeIf('admin.rfaCommentStatuses.relationships.forStatusRfas', ['rfas' => $rfaCommentStatus->forStatusRfas])
        </div>
    </div>
</div>

@endsection