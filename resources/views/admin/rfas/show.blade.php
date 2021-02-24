@extends('layouts.admin')

@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.rfa.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.rfas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                            <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.document_status') }}
                                    </th>
                                    <td>
                                        {{ $rfa->document_status->status_name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.bill') }}
                                    </th>
                                    <td>
                                        {{ $rfa->bill }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.title_eng') }}
                                    </th>
                                    <td>
                                        {{ $rfa->title_eng }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $rfa->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.title_cn') }}
                                    </th>
                                    <td>
                                        {{ $rfa->title_cn }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.origin_number') }}
                                    </th>
                                    <td>
                                        {{ $rfa->origin_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $rfa->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.rfa_code') }}
                                    </th>
                                    <td>
                                        {{ $rfa->rfa_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.type') }}
                                    </th>
                                    <td>
                                        {{ $rfa->type->type_name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.worktype') }}
                                    </th>
                                    <td>
                                        {{ App\Rfa::WORKTYPE_SELECT[$rfa->worktype] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $rfa->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.wbs_level_3') }}
                                    </th>
                                    <td>
                                        {{ $rfa->wbs_level_3->wbs_level_3_code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.wbs_level_4') }}
                                    </th>
                                    <td>
                                        {{ $rfa->wbs_level_4->wbs_level_4_code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.submit_date') }}
                                    </th>
                                    <td>
                                        {{ $rfa->submit_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.issueby') }}
                                    </th>
                                    <td>
                                        {{ $rfa->issueby->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.assign') }}
                                    </th>
                                    <td>
                                        {{ $rfa->assign->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.note_1') }}
                                    </th>
                                    <td>
                                        {!! $rfa->note_1 !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.file_upload_1') }}
                                    </th>
                                    <td>
                                        @foreach($rfa->file_upload_1 as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.downloadFile') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.qty_page') }}
                                    </th>
                                    <td>
                                        {{ $rfa->qty_page }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.commercial_file_upload') }}
                                    </th>
                                    <td>
                                        @foreach($rfa->commercial_file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.downloadFile') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.spec_ref_no') }}
                                    </th>
                                    <td>
                                        {{ $rfa->spec_ref_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.clause') }}
                                    </th>
                                    <td>
                                        {{ $rfa->clause }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.contract_drawing_no') }}
                                    </th>
                                    <td>
                                        {{ $rfa->contract_drawing_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.incoming_number') }}
                                    </th>
                                    <td>
                                        {{ $rfa->incoming_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.action_by') }}
                                    </th>
                                    <td>
                                        {{ $rfa->action_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.comment_by') }}
                                    </th>
                                    <td>
                                        {{ $rfa->comment_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.information_by') }}
                                    </th>
                                    <td>
                                        {{ $rfa->information_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.receive_date') }}
                                    </th>
                                    <td>
                                        {{ $rfa->receive_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.target_date') }}
                                    </th>
                                    <td>
                                        {{ $rfa->target_date }}
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.distribute_date') }}
                                    </th>
                                    <td>
                                        {{ $rfa->distribute_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.note_2') }}
                                    </th>
                                    <td>
                                        {{ $rfa->note_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.comment_status') }}
                                    </th>
                                    <td>
                                        {{ $rfa->comment_status->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.note_3') }}
                                    </th>
                                    <td>
                                        {{ $rfa->note_3 }}
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.document_ref') }}
                                    </th>
                                    <td>
                                        {{ $rfa->document_ref }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.document_file_upload') }}
                                    </th>
                                <td>
                                        @foreach($rfa->document_file_upload as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.downloadFile') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.document_description') }}
                                    </th>
                                    <td>
                                        {{ $rfa->document_description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.process_date') }}
                                    </th>
                                    <td>
                                        {{ $rfa->process_date }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.outgoing_number') }}
                                    </th>
                                    <td>
                                        {{ $rfa->outgoing_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.for_status') }}
                                    </th>
                                    <td>
                                        {{ $rfa->for_status->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.note_4') }}
                                    </th>
                                    <td>
                                        {{ $rfa->note_4 }}
                                    </td>
                                </tr>
                              
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.hardcopy_date') }}
                                    </th>
                                    <td>
                                        {{ $rfa->hardcopy_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.outgoing_date') }}
                                    </th>
                                    <td>
                                        {{ $rfa->outgoing_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.rfa.fields.team') }}
                                    </th>
                                    <td>
                                        {{ $rfa->team->code ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p hidden="true">{{ $check_distribute = 0 }}</p>
                        <p hidden="true">{{ $check_process = 0 }}</p>
                        <p hidden="true">{{ $check_approve = 0 }}</p>
                        <p hidden="true">{{ $check_reviewd = 0 }}</p>

                        <textarea style="display:none; class="form-control" name="note_1" id="note_1">{!! $rfa->note_1 !!}</textarea>
                        <textarea style="display:none; class="form-control" name="note_2" id="note_2">{!! $rfa->note_2 !!}</textarea>
                        <textarea style="display:none; class="form-control" name="note_3" id="note_3">{!! $rfa->note_3 !!}</textarea>
                        <textarea style="display:none; class="form-control" name="note_4" id="note_4">{!! $rfa->note_4 !!}</textarea>



                        @if(!empty($rfa->distribute_by->signature))
                            <img style="display:none" crossorigin="Anonymous" id="distribute_sign" src="{{ $rfa->distribute_by->signature->getUrl() }}"/> 
                             <p id="distribute_sign_base64" hidden="true">
                             </p> 
                             <p hidden="true">{{ $check_distribute = 1 }}</p>
                        @else
                             <p hidden="true">{{ $check_distribute = 0 }}</p>
                        @endif

                        @if(!empty($rfa->comment_status->id))
                            @if(!empty($rfa->action_by->signature))
                                <img style="display:none" crossorigin="Anonymous" id="process_sign" src="{{ $rfa->action_by->signature->getUrl() }}"/> 
                                 <p id="distribute_sign_base64" hidden="true">
                                 </p> 
                                 <p hidden="true">{{ $check_process = 1 }}</p>
                            @else
                                <p hidden="true">{{ $check_process = 0 }}</p>
                            @endif
                        @endif

                        @if(!empty($rfa->for_status->id))
                             <p hidden="true">{{ $check_approve = 1 }}</p>
                        @else
                             <p hidden="true">{{ $check_approve = 0 }}</p>
                        @endif

                        @if(!empty($rfa->reviewed_by->id))
                            @if(!empty($rfa->action_by->signature))
                                <img style="display:none" id="reviewed_sign" src="{{ $rfa->reviewed_by->signature->getUrl() }}"/> 
                                 <p id="distribute_sign_base64" hidden="true">
                                 </p> 
                                 <p hidden="true">{{ $check_reviewd = 1 }}</p>
                            @else
                                <p hidden="true">{{ $check_reviewd = 0 }}</p>
                            @endif
                        @endif



                          
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.rfas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
    
                            <a class="btn btn-success" href="{{ route('admin.rfas.createReportRFA',$rfa->id) }}">
                                View RFA & Submittals Form
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
