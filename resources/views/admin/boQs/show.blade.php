@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.boQ.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.bo-qs.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boQ.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $boQ->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boQ.fields.code') }}
                                    </th>
                                    <td>
                                        {{ $boQ->code }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.bo-qs.index') }}">
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
                        <a href="#boq_wbslevelfours" aria-controls="boq_wbslevelfours" role="tab" data-toggle="tab">
                            {{ trans('cruds.wbslevelfour.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="boq_wbslevelfours">
                        @includeIf('admin.boQs.relationships.boqWbslevelfours', ['wbslevelfours' => $boQ->boqWbslevelfours])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection