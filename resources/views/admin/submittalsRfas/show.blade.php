@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.submittalsRfa.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.submittals-rfas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $submittalsRfa->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.item_no') }}
                                    </th>
                                    <td>
                                        {{ $submittalsRfa->item_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $submittalsRfa->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.qty_sets') }}
                                    </th>
                                    <td>
                                        {{ $submittalsRfa->qty_sets }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.review_status') }}
                                    </th>
                                    <td>
                                        {{ $submittalsRfa->review_status->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.date_returned') }}
                                    </th>
                                    <td>
                                        {{ $submittalsRfa->date_returned }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $submittalsRfa->remarks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.submittalsRfa.fields.on_rfa') }}
                                    </th>
                                    <td>
                                        {{ $submittalsRfa->on_rfa->rfa_code ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.submittals-rfas.index') }}">
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