@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.rfaCommentStatus.title') }}
                </div>
                <div class="panel-body">
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

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.relatedData') }}
                </div>
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    <li role="presentation">
                        <a href="#comment_status_rfas" aria-controls="comment_status_rfas" role="tab" data-toggle="tab">
                            {{ trans('cruds.rfa.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#for_status_rfas" aria-controls="for_status_rfas" role="tab" data-toggle="tab">
                            {{ trans('cruds.rfa.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#review_status_submittals_rfas" aria-controls="review_status_submittals_rfas" role="tab" data-toggle="tab">
                            {{ trans('cruds.submittalsRfa.title') }}
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
                    <div class="tab-pane" role="tabpanel" id="review_status_submittals_rfas">
                        @includeIf('admin.rfaCommentStatuses.relationships.reviewStatusSubmittalsRfas', ['submittalsRfas' => $rfaCommentStatus->reviewStatusSubmittalsRfas])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection