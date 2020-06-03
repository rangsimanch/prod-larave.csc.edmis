@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.checkList.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.check-lists.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.work_type') }}
                                    </th>
                                    <td>
                                        {{ $checkList->work_type->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.work_title') }}
                                    </th>
                                    <td>
                                        {{ $checkList->work_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $checkList->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.date_of_work_done') }}
                                    </th>
                                    <td>
                                        {{ $checkList->date_of_work_done }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.name_of_inspector') }}
                                    </th>
                                    <td>
                                        {{ $checkList->name_of_inspector->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.thai_or_chinese_work') }}
                                    </th>
                                    <td>
                                        {{ App\CheckList::THAI_OR_CHINESE_WORK_SELECT[$checkList->thai_or_chinese_work] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $checkList->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $checkList->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkList.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($checkList->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.check-lists.index') }}">
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