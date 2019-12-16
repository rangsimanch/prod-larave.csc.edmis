@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.rfatype.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rfatypes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.rfatype.fields.id') }}
                        </th>
                        <td>
                            {{ $rfatype->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfatype.fields.type_name') }}
                        </th>
                        <td>
                            {{ $rfatype->type_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.rfatype.fields.type_code') }}
                        </th>
                        <td>
                            {{ $rfatype->type_code }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rfatypes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>


    </div>
</div>
@endsection