@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.provisionalSum.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.provisional-sums.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.provisionalSum.fields.bill_no') }}
                                    </th>
                                    <td>
                                        {{ $provisionalSum->bill_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.provisionalSum.fields.item_no') }}
                                    </th>
                                    <td>
                                        {{ $provisionalSum->item_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.provisionalSum.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $provisionalSum->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.provisionalSum.fields.name_of_ps') }}
                                    </th>
                                    <td>
                                        {{ $provisionalSum->name_of_ps }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.provisionalSum.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($provisionalSum->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.provisional-sums.index') }}">
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