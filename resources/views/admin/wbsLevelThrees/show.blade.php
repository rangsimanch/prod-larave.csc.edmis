@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.wbsLevelThree.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.wbs-level-threes.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.wbsLevelThree.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $wbsLevelThree->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.wbsLevelThree.fields.wbs_level_3_name') }}
                                    </th>
                                    <td>
                                        {{ $wbsLevelThree->wbs_level_3_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.wbsLevelThree.fields.wbs_level_3_code') }}
                                    </th>
                                    <td>
                                        {{ $wbsLevelThree->wbs_level_3_code }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.wbs-level-threes.index') }}">
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
                        <a href="#wbs_level3_rfas" aria-controls="wbs_level3_rfas" role="tab" data-toggle="tab">
                            {{ trans('cruds.rfa.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="wbs_level3_rfas">
                        @includeIf('admin.wbsLevelThrees.relationships.wbsLevel3Rfas', ['rfas' => $wbsLevelThree->wbsLevel3Rfas])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection