@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.checkSheet.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.check-sheets.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.work_type') }}
                                    </th>
                                    <td>
                                        {{ $checkSheet->work_type->work_title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.work_title') }}
                                    </th>
                                    <td>
                                        {{ $checkSheet->work_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $checkSheet->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.date_of_work_done') }}
                                    </th>
                                    <td>
                                        {{ $checkSheet->date_of_work_done }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.name_of_inspector') }}
                                    </th>
                                    <td>
                                        {{ $checkSheet->name_of_inspector->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.thai_or_chinese_work') }}
                                    </th>
                                    <td>
                                        {{ App\CheckSheet::THAI_OR_CHINESE_WORK_SELECT[$checkSheet->thai_or_chinese_work] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $checkSheet->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $checkSheet->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.checkSheet.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($checkSheet->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.check-sheets.index') }}">
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