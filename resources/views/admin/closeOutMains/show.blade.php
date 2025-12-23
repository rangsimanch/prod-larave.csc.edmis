@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.closeOutMain.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-mains.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\CloseOutMain::STATUS_SELECT[$closeOutMain->status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.closeout_subject') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->closeout_subject->subject ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.detail') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->detail }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.description') }}
                                    </th>
                                    <td>
                                        {!! $closeOutMain->description !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.quantity') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->quantity }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.ref_documents') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->ref_documents }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.final_file') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutMain->final_file as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.closeout_url') }}
                                    </th>
                                    <td>
                                        @foreach($closeOutMain->closeout_urls as $key => $closeout_url)
                                            <span class="label label-info">{{ $closeout_url->filename }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.ref_rfa_text') }}
                                    </th>
                                    <td>
                                        {{ $closeOutMain->ref_rfa_text }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.closeOutMain.fields.remark') }}
                                    </th>
                                    <td>
                                        {!! $closeOutMain->remark !!}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.close-out-mains.index') }}">
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