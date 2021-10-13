@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.srtOther.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-others.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.docuement_status') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->docuement_status->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.constuction_contract') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->constuction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.document_type') }}
                                    </th>
                                    <td>
                                        {{ App\SrtOther::DOCUMENT_TYPE_SELECT[$srtOther->document_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.incoming_date') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->incoming_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.refer_to') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->refer_to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.from_text') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->from_text }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.from') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->from->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.to_text') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->to_text }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.to') }}
                                    </th>
                                    <td>
                                        @foreach($srtOther->tos as $key => $to)
                                            <span class="label label-info">{{ $to->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.attachments') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->attachments }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.speed_class') }}
                                    </th>
                                    <td>
                                        {{ App\SrtOther::SPEED_CLASS_SELECT[$srtOther->speed_class] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.objective') }}
                                    </th>
                                    <td>
                                        {{ App\SrtOther::OBJECTIVE_SELECT[$srtOther->objective] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.signatory') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->signatory }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.document_storage') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->document_storage }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.close_date') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->close_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($srtOther->file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.save_for') }}
                                    </th>
                                    <td>
                                        {{ App\SrtOther::SAVE_FOR_SELECT[$srtOther->save_for] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.close_by_text') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->close_by_text }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.srtOther.fields.close_by') }}
                                    </th>
                                    <td>
                                        {{ $srtOther->close_by->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.srt-others.index') }}">
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