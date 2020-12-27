@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.addLetter.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.add-letters.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.letter_type') }}
                                    </th>
                                    <td>
                                        {{ App\AddLetter::LETTER_TYPE_SELECT[$addLetter->letter_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.letter_no') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->letter_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.speed_class') }}
                                    </th>
                                    <td>
                                        {{ App\AddLetter::SPEED_CLASS_SELECT[$addLetter->speed_class] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.objective') }}
                                    </th>
                                    <td>
                                        {{ App\AddLetter::OBJECTIVE_SELECT[$addLetter->objective] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.sender') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->sender->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.sent_date') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->sent_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.receiver') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->receiver->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.received_date') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->received_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.cc_to') }}
                                    </th>
                                    <td>
                                        @foreach($addLetter->cc_tos as $key => $cc_to)
                                            <span class="label label-info">{{ $cc_to->code }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.letter_upload') }}
                                    </th>
                                    <td>
                                        @foreach($addLetter->letter_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.letter_iso_no') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->letter_iso_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.create_by') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->create_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.receive_by') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->receive_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.mask_as_received') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $addLetter->mask_as_received ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addLetter.fields.note') }}
                                    </th>
                                    <td>
                                        {{ $addLetter->note }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.add-letters.index') }}">
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