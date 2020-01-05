@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.wbslevelfour.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.wbslevelfours.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.wbslevelfour.fields.wbs_level_4_name') }}
                                    </th>
                                    <td>
                                        {{ $wbslevelfour->wbs_level_4_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.wbslevelfour.fields.wbs_level_4_code') }}
                                    </th>
                                    <td>
                                        {{ $wbslevelfour->wbs_level_4_code }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.wbslevelfours.index') }}">
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
                        <a href="#wbs_level4_rfas" aria-controls="wbs_level4_rfas" role="tab" data-toggle="tab">
                            {{ trans('cruds.rfa.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="wbs_level4_rfas">
                        @includeIf('admin.wbslevelfours.relationships.wbsLevel4Rfas', ['rfas' => $wbslevelfour->wbsLevel4Rfas])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection