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

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#type_rfas" role="tab" data-toggle="tab">
                {{ trans('cruds.rfa.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="type_rfas">
            @includeIf('admin.rfatypes.relationships.typeRfas', ['rfas' => $rfatype->typeRfas])
        </div>
    </div>
</div>

@endsection