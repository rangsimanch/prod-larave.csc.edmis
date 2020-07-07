@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.boqItem.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.boq-items.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.boq') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->boq->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.code') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.unit') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->unit }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.quantity') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->quantity }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.unit_rate') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->unit_rate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.amount') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.factor_f') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->factor_f }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.unit_rate_x_ff') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->unit_rate_x_ff }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.total_amount') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->total_amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.boqItem.fields.remark') }}
                                    </th>
                                    <td>
                                        {{ $boqItem->remark }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.boq-items.index') }}">
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