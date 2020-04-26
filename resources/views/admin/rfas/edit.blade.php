@extends('layouts.admin')
@section('content')


<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.rfa.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.rfas.update", [$rfa->id]) }}"  class="swa-confirm" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        
                        @can('rfa_panel_a')
                        <legend> Constractor RFA Submittal </legend>
                        
                         <div class="form-group {{ $errors->has('purpose_for') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.rfa.fields.purpose_for') }}</label>
                            <select class="form-control" name="purpose_for" id="purpose_for">
                                <option value disabled {{ old('purpose_for', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Rfa::PURPOSE_FOR_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('purpose_for', $rfa->purpose_for) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('purpose_for'))
                                <span class="help-block" role="alert">{{ $errors->first('purpose_for') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.purpose_for_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('bill') ? 'has-error' : '' }}">
                            <label for="bill">{{ trans('cruds.rfa.fields.bill') }}</label>
                            <input class="form-control" type="text" name="bill" id="bill" value="{{ old('bill', $rfa->bill) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.bill_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title_eng') ? 'has-error' : '' }}">
                            <label for="title_eng">{{ trans('cruds.rfa.fields.title_eng') }}</label>
                            <input class="form-control" type="text" name="title_eng" id="title_eng" value="{{ old('title_eng', $rfa->title_eng) }}">
                            @if($errors->has('title_eng'))
                                <span class="help-block" role="alert">{{ $errors->first('title_eng') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_eng_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title">{{ trans('cruds.rfa.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $rfa->title) }}">
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('title_cn') ? 'has-error' : '' }}">
                            <label for="title_cn">{{ trans('cruds.rfa.fields.title_cn') }}</label>
                            <input class="form-control" type="text" name="title_cn" id="title_cn" value="{{ old('title_cn', $rfa->title_cn) }}">
                            @if($errors->has('title_cn'))
                                <span class="help-block" role="alert">{{ $errors->first('title_cn') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.title_cn_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('origin_number') ? 'has-error' : '' }}">
                            <label for="origin_number">{{ trans('cruds.rfa.fields.origin_number') }}</label>
                            <input class="form-control" type="text" name="origin_number" id="origin_number" value="{{ old('origin_number', $rfa->origin_number) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.origin_number_helper') }}</span>


                      {{--   </div>
                        <!-- <div class="form-group {{ $errors->has('document_number') ? 'has-error' : '' }}">
                            <label for="document_number">{{ trans('cruds.rfa.fields.document_number') }}</label>
                            <input class="form-control" type="text" name="document_number" id="document_number" value="{{ old('document_number', $rfa->document_number) }}">
                            @if($errors->has('document_number'))
                                <span class="help-block" role="alert">{{ $errors->first('document_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_number_helper') }}</span>
                        </div> -->
                        <!-- <div class="form-group {{ $errors->has('rfa_code') ? 'has-error' : '' }}">
                            <label for="rfa_code">{{ trans('cruds.rfa.fields.rfa_code') }}</label>
                            <input class="form-control" type="text" name="rfa_code" id="rfa_code" value="{{ old('rfa_code', $rfa->rfa_code) }}">
                            @if($errors->has('rfa_code'))
                                <span class="help-block" role="alert">{{ $errors->first('rfa_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.rfa_code_helper') }}</span>
                        </div> -->
                        <!-- <div class="form-group {{ $errors->has('review_time') ? 'has-error' : '' }}">
                            <label for="review_time">{{ trans('cruds.rfa.fields.review_time') }}</label>
                            <input class="form-control" type="number" name="review_time" id="review_time" value="{{ old('review_time', $rfa->review_time) }}" step="1">
                            @if($errors->has('review_time'))
                                <span class="help-block" role="alert">{{ $errors->first('review_time') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.review_time_helper') }}</span>
                        </div> --> --}}

                        
                        <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                            <label for="type_id" class="required">{{ trans('cruds.rfa.fields.type') }}</label>
                            <select class="form-control select2" name="type_id" id="type_id" required>
                                @foreach($types as $id => $type)
                                    <option value="{{ $id }}" {{ ($rfa->type ? $rfa->type->id : old('type_id')) == $id ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type_id'))
                                <span class="help-block" role="alert">{{ $errors->first('type_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('worktype') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.rfa.fields.worktype') }}</label>
                            <select class="form-control" name="worktype" id="worktype" required>
                                <option value disabled {{ old('worktype', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Rfa::WORKTYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('worktype', $rfa->worktype) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('worktype'))
                                <span class="help-block" role="alert">{{ $errors->first('worktype') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.worktype_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('construction_contract') ? 'has-error' : '' }}">
                            <label class="required" for="construction_contract_id">{{ trans('cruds.rfa.fields.construction_contract') }}</label>
                            <select class="form-control select2" name="construction_contract_id" id="construction_contract_id" required>
                                @foreach($construction_contracts as $id => $construction_contract)
                                    <option value="{{ $id }}" {{ ($rfa->construction_contract ? $rfa->construction_contract->id : old('construction_contract_id')) == $id ? 'selected' : '' }}>{{ $construction_contract }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('construction_contract_id'))
                                <span class="help-block" role="alert">{{ $errors->first('construction_contract_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.construction_contract_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('wbs_level_3') ? 'has-error' : '' }}">
                            <label  class="required" for="wbs_level_3_id">{{ trans('cruds.rfa.fields.wbs_level_3') }}</label>
                            <select class="form-control select2" name="wbs_level_3_id" id="wbs_level_3_id">
                                @foreach($wbs_level_3s as $id => $wbs_level_3)
                                    <option value="{{ $id }}" {{ ($rfa->wbs_level_3 ? $rfa->wbs_level_3->id : old('wbs_level_3_id')) == $id ? 'selected' : '' }}>{{ $wbs_level_3 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wbs_level_3_id'))
                                <span class="help-block" role="alert">{{ $errors->first('wbs_level_3_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.wbs_level_3_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('wbs_level_4') ? 'has-error' : '' }}">
                            <label  class="required" for="wbs_level_4_id">{{ trans('cruds.rfa.fields.wbs_level_4') }}</label>
                            <select class="form-control select2" name="wbs_level_4_id" id="wbs_level_4_id">
                                @foreach($wbs_level_4s as $id => $wbs_level_4)
                                    <option value="{{ $id }}" {{ ($rfa->wbs_level_4 ? $rfa->wbs_level_4->id : old('wbs_level_4_id')) == $id ? 'selected' : '' }}>{{ $wbs_level_4 }}</option>
                                @endforeach
                            </select>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.wbs_level_4_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('submit_date') ? 'has-error' : '' }}">
                            <label for="submit_date">{{ trans('cruds.rfa.fields.submit_date') }}</label>
                            <input class="form-control date" type="text" name="submit_date" id="submit_date" value="{{ old('submit_date', $rfa->submit_date) }}">
                            @if($errors->has('submit_date'))
                                <span class="help-block" role="alert">{{ $errors->first('submit_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.submit_date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('issueby') ? 'has-error' : '' }}">
                            <label for="issueby_id">{{ trans('cruds.rfa.fields.issueby') }}</label>
                            <select class="form-control select2" name="issueby_id" id="issueby_id">
                                @foreach($issuebies as $id => $issueby)
                                    <option value="{{ $id }}" {{ ($rfa->issueby ? $rfa->issueby->id : old('issueby_id')) == $id ? 'selected' : '' }}>{{ $issueby }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('issueby_id'))
                                <span class="help-block" role="alert">{{ $errors->first('issueby_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.issueby_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('assign') ? 'has-error' : '' }}">
                            <label for="assign_id">{{ trans('cruds.rfa.fields.assign') }}</label>
                            <select class="form-control select2" name="assign_id" id="assign_id">
                                @foreach($assigns as $id => $assign)
                                    <option value="{{ $id }}" {{ ($rfa->assign ? $rfa->assign->id : old('assign_id')) == $id ? 'selected' : '' }}>{{ $assign }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('assign_id'))
                                <span class="help-block" role="alert">{{ $errors->first('assign_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.assign_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('note_1') ? 'has-error' : '' }}">
                            <label for="note_1">{{ trans('cruds.rfa.fields.note_1') }}</label>
                            <textarea class="form-control" name="note_1" id="note_1">{!! old('note_1', $rfa->note_1) !!}</textarea>
                            @if($errors->has('note_1'))
                                <span class="help-block" role="alert">{{ $errors->first('note_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_1_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('attach_file_name') ? 'has-error' : '' }}">
                            <label for="attach_file_name">{{ trans('cruds.rfa.fields.attach_file_name') }}</label>
                            <input class="form-control" type="text" name="attach_file_name" id="attach_file_name" value="{{ old('attach_file_name', '') }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.attach_file_name_helper') }}</span>
                        </div>
                        @endcan


                        {{-- Attach File --}}
                        @if($rfa->document_status->id == 1)
                            @if($rfa->cec_sign == 2 && $rfa->cec_stamp == 2)
                                <div class="form-group {{ $errors->has('file_upload_1') ? 'has-error' : '' }}">
                                    <label for="file_upload_1">{{ trans('cruds.rfa.fields.file_upload_1') }}</label>
                                    <div class="needsclick dropzone" id="file_upload_1-dropzone">
                                    </div>
                                    @if($errors->has('file_upload_1'))
                                        <span class="help-block" role="alert">{{ $errors->first('file_upload_1') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.rfa.fields.file_upload_1_helper') }}</span>
                                </div>
                            @else
                                <div hidden="true" class="form-group {{ $errors->has('file_upload_1') ? 'has-error' : '' }}">
                                    <label for="file_upload_1">{{ trans('cruds.rfa.fields.file_upload_1') }}</label>
                                    <div class="needsclick dropzone" id="file_upload_1-dropzone">
                                    </div>
                                    @if($errors->has('file_upload_1'))
                                        <span class="help-block" role="alert">{{ $errors->first('file_upload_1') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.rfa.fields.file_upload_1_helper') }}</span>
                                </div>
                            @endif
                        @else
                        <div hidden="true" class="form-group {{ $errors->has('file_upload_1') ? 'has-error' : '' }}">
                            <label for="file_upload_1">{{ trans('cruds.rfa.fields.file_upload_1') }}</label>
                            <div class="needsclick dropzone" id="file_upload_1-dropzone">
                            </div>
                            @if($errors->has('file_upload_1'))
                                <span class="help-block" role="alert">{{ $errors->first('file_upload_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.file_upload_1_helper') }}</span>
                        </div>
                        @endif

                        @can('rfa_panel_a')
                        <div class="form-group {{ $errors->has('qty_page') ? 'has-error' : '' }}">
                            <label for="qty_page">{{ trans('cruds.rfa.fields.qty_page') }}</label>
                            <input class="form-control" type="text" name="qty_page" id="qty_page" value="{{ old('qty_page', $rfa->qty_page) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.qty_page_helper') }}</span>
                        </div>
                        @endcan

                         <!-- @if($rfa->document_status->id == 1)
                            @if($rfa->cec_sign == 2 && $rfa->cec_stamp == 2)
                                <div class="form-group {{ $errors->has('commercial_file_upload') ? 'has-error' : '' }}">
                                    <label for="commercial_file_upload">{{ trans('cruds.rfa.fields.commercial_file_upload') }}</label>
                                    <div class="needsclick dropzone" id="commercial_file_upload-dropzone">
                                    </div>
                                    @if($errors->has(''))
                                        <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.rfa.fields.commercial_file_upload_helper') }}</span>
                                </div>
                            @else
                                    <div hidden="true" class="form-group {{ $errors->has('commercial_file_upload') ? 'has-error' : '' }}">
                                        <label for="commercial_file_upload">{{ trans('cruds.rfa.fields.commercial_file_upload') }}</label>
                                        <div class="needsclick dropzone" id="commercial_file_upload-dropzone">
                                        </div>
                                        @if($errors->has(''))
                                            <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                                        @endif
                                        <span class="help-block">{{ trans('cruds.rfa.fields.commercial_file_upload_helper') }}</span>
                                    </div>
                            @endif
                        @else
                            <div hidden="true" class="form-group {{ $errors->has('commercial_file_upload') ? 'has-error' : '' }}">
                                <label for="commercial_file_upload">{{ trans('cruds.rfa.fields.commercial_file_upload') }}</label>
                                <div class="needsclick dropzone" id="commercial_file_upload-dropzone">
                                </div>
                                @if($errors->has(''))
                                    <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.rfa.fields.commercial_file_upload_helper') }}</span>
                             </div>
                        @endif -->

                        @can('rfa_panel_a')
                        <div class="form-group {{ $errors->has('spec_ref_no') ? 'has-error' : '' }}">
                            <label for="spec_ref_no">{{ trans('cruds.rfa.fields.spec_ref_no') }}</label>
                            <input class="form-control" type="text" name="spec_ref_no" id="spec_ref_no" value="{{ old('spec_ref_no', $rfa->spec_ref_no) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.spec_ref_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('clause') ? 'has-error' : '' }}">
                            <label for="clause">{{ trans('cruds.rfa.fields.clause') }}</label>
                            <input class="form-control" type="text" name="clause" id="clause" value="{{ old('clause', $rfa->clause) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.clause_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('contract_drawing_no') ? 'has-error' : '' }}">
                            <label for="contract_drawing_no">{{ trans('cruds.rfa.fields.contract_drawing_no') }}</label>
                            <input class="form-control" type="text" name="contract_drawing_no" id="contract_drawing_no" value="{{ old('contract_drawing_no', $rfa->contract_drawing_no) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.contract_drawing_no_helper') }}</span>
                        </div>


                        @can('rfa_cec_sign')
                        <div class="form-group {{ $errors->has('cec_sign') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.rfa.fields.cec_sign') }}</label>
                            @foreach(App\Rfa::CEC_SIGN_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="cec_sign_{{ $key }}" name="cec_sign" value="{{ $key }}" {{ old('cec_sign', $rfa->cec_sign) === (string) $key ? 'checked' : '' }} onclick="check_sign()">
                                    <label for="cec_sign_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('cec_sign'))
                                <span class="help-block" role="alert">{{ $errors->first('cec_sign') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.cec_sign_helper') }}</span>
                        </div>
                        @endcan

                        @can('rfa_cec_stamp')
                        <div class="form-group {{ $errors->has('cec_stamp') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.rfa.fields.cec_stamp') }}</label>
                            @foreach(App\Rfa::CEC_STAMP_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="cec_stamp_{{ $key }}" name="cec_stamp" value="{{ $key }}" {{ old('cec_stamp', $rfa->cec_stamp) === (string) $key ? 'checked' : '' }} onclick="check_stamp()">
                                    <label for="cec_stamp_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('cec_stamp'))
                                <span class="help-block" role="alert">{{ $errors->first('cec_stamp') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.cec_stamp_helper') }}</span>
                        </div>
                        @endcan

                        @endcan

                        @if($rfa->document_status->id  == 1)
                        @can('rfa_panel_b')
                        <legend>Incoming Distribution</legend>

                        <div class="form-group {{ $errors->has('action_by') ? 'has-error' : '' }}">
                            <label for="action_by_id">{{ trans('cruds.rfa.fields.action_by') }}</label>
                            <select class="form-control select2" name="action_by_id" id="action_by_id">
                                @foreach($action_bies as $id => $action_by)
                                    <option value="{{ $id }}" {{ ($rfa->action_by ? $rfa->action_by->id : old('action_by_id')) == $id ? 'selected' : '' }}>{{ $action_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.action_by_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('comment_by') ? 'has-error' : '' }}">
                            <label for="comment_by_id">{{ trans('cruds.rfa.fields.comment_by') }}</label>
                            <select class="form-control select2" name="comment_by_id" id="comment_by_id">
                                @foreach($comment_bies as $id => $comment_by)
                                    <option value="{{ $id }}" {{ ($rfa->comment_by ? $rfa->comment_by->id : old('comment_by_id')) == $id ? 'selected' : '' }}>{{ $comment_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('comment_by_id'))
                                <span class="help-block" role="alert">{{ $errors->first('comment_by_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.comment_by_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('information_by') ? 'has-error' : '' }}">
                            <label for="information_by_id">{{ trans('cruds.rfa.fields.information_by') }}</label>
                            <select class="form-control select2" name="information_by_id" id="information_by_id">
                                @foreach($information_bies as $id => $information_by)
                                    <option value="{{ $id }}" {{ ($rfa->information_by ? $rfa->information_by->id : old('information_by_id')) == $id ? 'selected' : '' }}>{{ $information_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('information_by_id'))
                                <span class="help-block" role="alert">{{ $errors->first('information_by_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.information_by_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('receive_date') ? 'has-error' : '' }}">
                            <label for="receive_date">{{ trans('cruds.rfa.fields.receive_date') }}</label>
                            <input class="form-control date r_date" type="text" name="receive_date" id="receive_date" value="{{ old('receive_date', $rfa->receive_date) }}">
                            @if($errors->has('receive_date'))
                                <span class="help-block" role="alert">{{ $errors->first('receive_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.receive_date_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('doc_count') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.rfa.fields.doc_count') }}</label>
                            <select class="form-control doc_counter" id="doc_count" name="doc_count">
                            <option value disabled {{ old('doc_count', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Rfa::DOC_COUNT_RADIO as $key => $label)
                            <option value="{{ $key }}" {{ old('doc_count', $rfa->doc_count) === (string) $key ? 'selected' : '' }}>{{ $label . ' Days' }}</option>
                            @endforeach
                            </select>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.doc_count_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('target_date') ? 'has-error' : '' }}">
                            <label for="target_date">{{ trans('cruds.rfa.fields.target_date') }}</label>
                            <input readonly class="form-control date" type="text" name="target_date" id="target_date" value="{{ old('target_date' , $rfa->target_date) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.target_date_helper') }}</span>
                        </div>

                        
                        <div class="form-group {{ $errors->has('note_2') ? 'has-error' : '' }}">
                            <label for="note_2">{{ trans('cruds.rfa.fields.note_2') }}</label>
                            <textarea class="form-control" name="note_2" id="note_2">{{ old('note_2', $rfa->note_2) }}</textarea>
                            @if($errors->has('note_2'))
                                <span class="help-block" role="alert">{{ $errors->first('note_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_2_helper') }}</span>
                        </div>
                        @endcan
                        @else
                        @can('rfa_admin')
                        <legend>Incoming Distribution</legend>

                        <div class="form-group {{ $errors->has('action_by') ? 'has-error' : '' }}">
                            <label for="action_by_id">{{ trans('cruds.rfa.fields.action_by') }}</label>
                            <select class="form-control select2" name="action_by_id" id="action_by_id">
                                @foreach($action_bies as $id => $action_by)
                                    <option value="{{ $id }}" {{ ($rfa->action_by ? $rfa->action_by->id : old('action_by_id')) == $id ? 'selected' : '' }}>{{ $action_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.action_by_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('comment_by') ? 'has-error' : '' }}">
                            <label for="comment_by_id">{{ trans('cruds.rfa.fields.comment_by') }}</label>
                            <select class="form-control select2" name="comment_by_id" id="comment_by_id">
                                @foreach($comment_bies as $id => $comment_by)
                                    <option value="{{ $id }}" {{ ($rfa->comment_by ? $rfa->comment_by->id : old('comment_by_id')) == $id ? 'selected' : '' }}>{{ $comment_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('comment_by_id'))
                                <span class="help-block" role="alert">{{ $errors->first('comment_by_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.comment_by_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('information_by') ? 'has-error' : '' }}">
                            <label for="information_by_id">{{ trans('cruds.rfa.fields.information_by') }}</label>
                            <select class="form-control select2" name="information_by_id" id="information_by_id">
                                @foreach($information_bies as $id => $information_by)
                                    <option value="{{ $id }}" {{ ($rfa->information_by ? $rfa->information_by->id : old('information_by_id')) == $id ? 'selected' : '' }}>{{ $information_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('information_by_id'))
                                <span class="help-block" role="alert">{{ $errors->first('information_by_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.information_by_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('receive_date') ? 'has-error' : '' }}">
                            <label for="receive_date">{{ trans('cruds.rfa.fields.receive_date') }}</label>
                            <input class="form-control date r_date" type="text" name="receive_date" id="receive_date" value="{{ old('receive_date', $rfa->receive_date) }}">
                            @if($errors->has('receive_date'))
                                <span class="help-block" role="alert">{{ $errors->first('receive_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.receive_date_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('doc_count') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.rfa.fields.doc_count') }}</label>
                            <select class="form-control doc_counter" id="doc_count" name="doc_count">
                            <option value disabled {{ old('doc_count', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Rfa::DOC_COUNT_RADIO as $key => $label)
                            <option value="{{ $key }}" {{ old('doc_count', $rfa->doc_count) === (string) $key ? 'selected' : '' }}>{{ $label . ' Days' }}</option>
                            @endforeach
                            </select>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.doc_count_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('target_date') ? 'has-error' : '' }}">
                            <label for="target_date">{{ trans('cruds.rfa.fields.target_date') }}</label>
                            <input readonly class="form-control date" type="text" name="target_date" id="target_date" value="{{ old('target_date' , $rfa->target_date) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.target_date_helper') }}</span>
                        </div>

                        
                        <div class="form-group {{ $errors->has('note_2') ? 'has-error' : '' }}">
                            <label for="note_2">{{ trans('cruds.rfa.fields.note_2') }}</label>
                            <textarea class="form-control" name="note_2" id="note_2">{{ old('note_2', $rfa->note_2) }}</textarea>
                            @if($errors->has('note_2'))
                                <span class="help-block" role="alert">{{ $errors->first('note_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_2_helper') }}</span>
                        </div>
                        @endcan
                        
                        @endif
                        
                        @if($rfa->document_status->id  == 2)
                        @can('rfa_panel_c')
                        <legend> CSC Outgoing (Specialist/Engineer) </legend>
                        <div class="form-group {{ $errors->has('comment_status') ? 'has-error' : '' }}">
                            <label for="comment_status_id">{{ trans('cruds.rfa.fields.comment_status') }}</label>
                            <select class="form-control select2" name="comment_status_id" id="comment_status_id">
                                @foreach($comment_statuses as $id => $comment_status)
                                    <option value="{{ $id }}" {{ ($rfa->comment_status ? $rfa->comment_status->id : old('comment_status_id')) == $id ? 'selected' : '' }}>{{ $comment_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('comment_status_id'))
                                <span class="help-block" role="alert">{{ $errors->first('comment_status_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.comment_status_helper') }}</span>
                        </div>

                        

                        <div class="form-group {{ $errors->has('note_3') ? 'has-error' : '' }}">
                            <label for="note_3">{{ trans('cruds.rfa.fields.note_3') }}</label>
                            <textarea class="form-control" name="note_3" id="note_3">{{ old('note_3', $rfa->note_3) }}</textarea>
                            @if($errors->has('note_3'))
                                <span class="help-block" role="alert">{{ $errors->first('note_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_3_helper') }}</span>
                        </div>


                         <div class="table-responsive">
                                <span id="result"> </span>
                                <label> {{ trans('cruds.submittalsRfa.title')}} </label>
                                
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="check" id="ApproveAll"> Approve All </input>
                                    </label>

                                    <label>
                                        <input type="checkbox" class="check" id="AsNoteAll"> Approve as Note All </input>
                                    </label>

                                    <label>
                                        <input type="checkbox" class="check" id="ResubmitAll"> Re-Submit All </input>
                                    </label>

                                    <label>
                                        <input type="checkbox" class="check" id="RejectAll"> Reject All </input>
                                    </label>
                                </div>
                                <table class="table table-bordered table-striped" id="submittal_table">
                                    <thead>
                                        <tr>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.item_no') }} </center></th>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.description') }} </center></th>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.qty_sets') }} </center></th>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.review_status') }} </center></th>
                                            <th width="15%"><center>{{ trans('cruds.submittalsRfa.fields.date_returned') }} </center></th>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.remarks') }} </center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <div class="form-group">
                                            @foreach($submittalsRfa as $id => $submittals )

                                                <td><input type="hidden" class="form-control" name="id_submittals[]" value="{{ $submittals->id }}"></td>
                                            <tr>
                                                <td><input readonly type="text" class="form-control" name="item[]" value=" {{ $submittals->item_no }}"/></td>
                                                <td><input readonly type="text" class="form-control" name="description[]" value=" {{ $submittals->description }}"/></td>
                                                <td><input readonly type="text" class="form-control" name="qty_sets[]" value=" {{ $submittals->qty_sets }}"/></td>
                                                <td>
                                                    <select class="form-control select2 check review_status" name="review_status[]" id="review_status">
                                                        @foreach($review_statuses as $id => $review_status)
                                                        <option value="{{ $id }}" {{ ($submittals->review_status ? $submittals->review_status_id : old('review_status_id')) == $id ? 'selected' : '' }}>{{ $review_status }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control date_returned" type="date" name="date_returned[]" id="date_returned">
                                                </td>
                                                <td>
                                                     <textarea class="form-control" name="remarks[]" id="remarks"></textarea>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </div>
                                    </tbody>
                                </table>
                        </div>
                        @endcan
                        
                        <!-- ADMIN EDIT  -->
                        @else
                        @can('rfa_admin')
                        <legend> CSC Outgoing (Specialist/Engineer) </legend>
                        <div class="form-group {{ $errors->has('comment_status') ? 'has-error' : '' }}">
                            <label for="comment_status_id">{{ trans('cruds.rfa.fields.comment_status') }}</label>
                            <select class="form-control select2" name="comment_status_id" id="comment_status_id">
                                @foreach($comment_statuses as $id => $comment_status)
                                    <option value="{{ $id }}" {{ ($rfa->comment_status ? $rfa->comment_status->id : old('comment_status_id')) == $id ? 'selected' : '' }}>{{ $comment_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('comment_status_id'))
                                <span class="help-block" role="alert">{{ $errors->first('comment_status_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.comment_status_helper') }}</span>
                        </div>

                        

                        <div class="form-group {{ $errors->has('note_3') ? 'has-error' : '' }}">
                            <label for="note_3">{{ trans('cruds.rfa.fields.note_3') }}</label>
                            <textarea class="form-control" name="note_3" id="note_3">{{ old('note_3', $rfa->note_3) }}</textarea>
                            @if($errors->has('note_3'))
                                <span class="help-block" role="alert">{{ $errors->first('note_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_3_helper') }}</span>
                        </div>


                         <div class="table-responsive">
                                <span id="result"> </span>
                                <label> {{ trans('cruds.submittalsRfa.title')}} </label>
                                
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="check" id="ApproveAll"> Approve All </input>
                                    </label>

                                    <label>
                                        <input type="checkbox" class="check" id="AsNoteAll"> Approve as Note All </input>
                                    </label>

                                    <label>
                                        <input type="checkbox" class="check" id="ResubmitAll"> Re-Submit All </input>
                                    </label>

                                    <label>
                                        <input type="checkbox" class="check" id="RejectAll"> Reject All </input>
                                    </label>
                                </div>
                                <table class="table table-bordered table-striped" id="submittal_table">
                                    <thead>
                                        <tr>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.item_no') }} </center></th>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.description') }} </center></th>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.qty_sets') }} </center></th>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.review_status') }} </center></th>
                                            <th width="15%"><center>{{ trans('cruds.submittalsRfa.fields.date_returned') }} </center></th>
                                            <th width="15%"><center> {{ trans('cruds.submittalsRfa.fields.remarks') }} </center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <div class="form-group">
                                            @foreach($submittalsRfa as $id => $submittals )

                                                <td><input type="hidden" class="form-control" name="id_submittals[]" value="{{ $submittals->id }}"></td>
                                            <tr>
                                                <td><input readonly type="text" class="form-control" name="item[]" value=" {{ $submittals->item_no }}"/></td>
                                                <td><input readonly type="text" class="form-control" name="description[]" value=" {{ $submittals->description }}"/></td>
                                                <td><input readonly type="text" class="form-control" name="qty_sets[]" value=" {{ $submittals->qty_sets }}"/></td>
                                                <td>
                                                    <select class="form-control select2 check review_status" name="review_status[]" id="review_status">
                                                        @foreach($review_statuses as $id => $review_status)
                                                        <option value="{{ $id }}" {{ ($submittals->review_status ? $submittals->review_status_id : old('review_status_id')) == $id ? 'selected' : '' }}>{{ $review_status }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control date_returned" type="date" name="date_returned[]" id="date_returned">
                                                </td>
                                                <td>
                                                     <textarea class="form-control" name="remarks[]" id="remarks"></textarea>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </div>
                                    </tbody>
                                </table>
                        </div>
                        @endcan
                        @endif

                            @if($rfa->document_status->id == 2)
                                <div class="form-group {{ $errors->has('document_file_upload') ? 'has-error' : '' }}">
                                    <label for="document_file_upload">{{ trans('cruds.rfa.fields.document_file_upload') }}</label>
                                    <div class="needsclick dropzone" id="document_file_upload-dropzone">
                                    </div>
                                    @if($errors->has(''))
                                        <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.rfa.fields.document_file_upload_helper') }}</span>
                                </div>
                            @else
                                <div hidden="true" class="form-group {{ $errors->has('document_file_upload') ? 'has-error' : '' }}">
                                    <label for="document_file_upload">{{ trans('cruds.rfa.fields.document_file_upload') }}</label>
                                    <div class="needsclick dropzone" id="document_file_upload-dropzone">
                                    </div>
                                    @if($errors->has(''))
                                        <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.rfa.fields.document_file_upload_helper') }}</span>
                                </div>
                            @endif
                            
                        @if($rfa->document_status->id  == 2)
                        @can('rfa_panel_c')
                        <div class="form-group {{ $errors->has('document_ref') ? 'has-error' : '' }}">
                            <label for="document_ref">{{ trans('cruds.rfa.fields.document_ref') }}</label>
                            <input class="form-control" type="text" name="document_ref" id="document_ref" value="{{ old('document_ref', $rfa->document_ref) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_ref_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_description') ? 'has-error' : '' }}">
                            <label for="document_description">{{ trans('cruds.rfa.fields.document_description') }}</label>
                            <textarea class="form-control" name="document_description" id="document_description">{{ old('document_description', $rfa->document_description) }}</textarea>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_description_helper') }}</span>
                        </div>
                        @endcan

                        <!-- ADMIN EDIT  -->
                        @else
                        @can('rfa_admin')
                        <div class="form-group {{ $errors->has('document_ref') ? 'has-error' : '' }}">
                            <label for="document_ref">{{ trans('cruds.rfa.fields.document_ref') }}</label>
                            <input class="form-control" type="text" name="document_ref" id="document_ref" value="{{ old('document_ref', $rfa->document_ref) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_ref_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('document_description') ? 'has-error' : '' }}">
                            <label for="document_description">{{ trans('cruds.rfa.fields.document_description') }}</label>
                            <textarea class="form-control" name="document_description" id="document_description">{{ old('document_description', $rfa->document_description) }}</textarea>
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.document_description_helper') }}</span>
                        </div>
                        @endcan
                        @endif


                        @if($rfa->document_status->id  == 3)
                        @can('rfa_panel_d')
                        <legend> CSC Outgoing </legend>
                        <div class="form-group {{ $errors->has('for_status') ? 'has-error' : '' }}">
                            <label for="for_status_id">{{ trans('cruds.rfa.fields.for_status') }}</label>
                            <select class="form-control select2" name="for_status_id" id="for_status_id">
                                @foreach($for_statuses as $id => $for_status)
                                    <option value="{{ $id }}" {{ ($rfa->for_status ? $rfa->for_status->id : old('for_status_id')) == $id ? 'selected' : '' }}>{{ $for_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('for_status_id'))
                                <span class="help-block" role="alert">{{ $errors->first('for_status_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.for_status_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('note_4') ? 'has-error' : '' }}">
                            <label for="note_4">{{ trans('cruds.rfa.fields.note_4') }}</label>
                            <textarea class="form-control" name="note_4" id="note_4">{{ old('note_4', $rfa->note_4) }}</textarea>
                            @if($errors->has('note_4'))
                                <span class="help-block" role="alert">{{ $errors->first('note_4') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_4_helper') }}</span>
                        </div>

                         <div class="form-group {{ $errors->has('reviewed_by') ? 'has-error' : '' }}">
                            <label for="reviewed_by_id">{{ trans('cruds.rfa.fields.reviewed_by') }}</label>
                            <select class="form-control select2" name="reviewed_by_id" id="reviewed_by_id">
                                @foreach($reviewed_bies as $id => $reviewed_by)
                                    <option value="{{ $id }}" {{ ($rfa->reviewed_by ? $rfa->reviewed_by->id : old('reviewed_by_id')) == $id ? 'selected' : '' }}>{{ $reviewed_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('reviewed_by'))
                                <span class="help-block" role="alert">{{ $errors->first('reviewed_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.reviewed_by_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('hardcopy_date') ? 'has-error' : '' }}">
                            <label for="hardcopy_date">{{ trans('cruds.rfa.fields.hardcopy_date') }}</label>
                            <input class="form-control date" type="text" name="hardcopy_date" id="hardcopy_date" value="{{ old('hardcopy_date', $rfa->hardcopy_date) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.hardcopy_date_helper') }}</span>
                        </div>
                        @endcan

                        <!-- ADMIN EDIT -->
                        @else
                        @can('rfa_admin')
                        <legend> CSC Outgoing </legend>
                        <div class="form-group {{ $errors->has('for_status') ? 'has-error' : '' }}">
                            <label for="for_status_id">{{ trans('cruds.rfa.fields.for_status') }}</label>
                            <select class="form-control select2" name="for_status_id" id="for_status_id">
                                @foreach($for_statuses as $id => $for_status)
                                    <option value="{{ $id }}" {{ ($rfa->for_status ? $rfa->for_status->id : old('for_status_id')) == $id ? 'selected' : '' }}>{{ $for_status }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('for_status_id'))
                                <span class="help-block" role="alert">{{ $errors->first('for_status_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.for_status_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('note_4') ? 'has-error' : '' }}">
                            <label for="note_4">{{ trans('cruds.rfa.fields.note_4') }}</label>
                            <textarea class="form-control" name="note_4" id="note_4">{{ old('note_4', $rfa->note_4) }}</textarea>
                            @if($errors->has('note_4'))
                                <span class="help-block" role="alert">{{ $errors->first('note_4') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.note_4_helper') }}</span>
                        </div>

                         <div class="form-group {{ $errors->has('reviewed_by') ? 'has-error' : '' }}">
                            <label for="reviewed_by_id">{{ trans('cruds.rfa.fields.reviewed_by') }}</label>
                            <select class="form-control select2" name="reviewed_by_id" id="reviewed_by_id">
                                @foreach($reviewed_bies as $id => $reviewed_by)
                                    <option value="{{ $id }}" {{ ($rfa->reviewed_by ? $rfa->reviewed_by->id : old('reviewed_by_id')) == $id ? 'selected' : '' }}>{{ $reviewed_by }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('reviewed_by'))
                                <span class="help-block" role="alert">{{ $errors->first('reviewed_by') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.reviewed_by_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('hardcopy_date') ? 'has-error' : '' }}">
                            <label for="hardcopy_date">{{ trans('cruds.rfa.fields.hardcopy_date') }}</label>
                            <input class="form-control date" type="text" name="hardcopy_date" id="hardcopy_date" value="{{ old('hardcopy_date', $rfa->hardcopy_date) }}">
                            @if($errors->has(''))
                                <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.rfa.fields.hardcopy_date_helper') }}</span>
                        </div>
                        @endcan
                        @endif

                        @can('rfa_admin')
                        <div class="form-group {{ $errors->has('commercial_file_upload') ? 'has-error' : '' }}">
                                    <label for="commercial_file_upload">{{ trans('cruds.rfa.fields.commercial_file_upload') }}</label>
                                    <div class="needsclick dropzone" id="commercial_file_upload-dropzone">
                                    </div>
                                    @if($errors->has(''))
                                        <span class="help-block" role="alert">{{ $errors->first('') }}</span>
                                    @endif
                                        <span class="help-block">{{ trans('cruds.rfa.fields.commercial_file_upload_helper') }}</span>
                        </div>
                        @endcan

                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.rfas.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>


                            <button class="btn btn-success" type="submit" id="save_form">
                                {{ trans('global.save') }}
                            </button>

                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/rfas/ckmedia', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', {{ $rfa->id ?? 0 }});
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    var uploadedFileUpload1Map = {}
Dropzone.options.fileUpload1Dropzone = {
    url: '{{ route('admin.rfas.storeMedia') }}',
    maxFilesize: 200, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 200
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="file_upload_1[]" value="' + response.name + '">')
      uploadedFileUpload1Map[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFileUpload1Map[file.name]
      }
      $('form').find('input[name="file_upload_1[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($rfa) && $rfa->file_upload_1)
          var files =
            {!! json_encode($rfa->file_upload_1) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="file_upload_1[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    var uploadedCommercialFileUploadMap = {}
Dropzone.options.commercialFileUploadDropzone = {
    url: '{{ route('admin.rfas.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="commercial_file_upload[]" value="' + response.name + '">')
      uploadedCommercialFileUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedCommercialFileUploadMap[file.name]
      }
      $('form').find('input[name="commercial_file_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($rfa) && $rfa->commercial_file_upload)
          var files =
            {!! json_encode($rfa->commercial_file_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="commercial_file_upload[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    var uploadedDocumentFileUploadMap = {}
Dropzone.options.documentFileUploadDropzone = {
    url: '{{ route('admin.rfas.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="document_file_upload[]" value="' + response.name + '">')
      uploadedDocumentFileUploadMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDocumentFileUploadMap[file.name]
      }
      $('form').find('input[name="document_file_upload[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($rfa) && $rfa->document_file_upload)
          var files =
            {!! json_encode($rfa->document_file_upload) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="document_file_upload[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>


<script type="text/javascript">
    $('.wbslv3').change(function(){
        if($(this).val() != ''){
            var select = $(this).val();
            console.log(select);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('admin.rfas.fetch') }}",
                method:"POST",
                data:{select:select , _token:_token},
                success:function(result){
                    //Action

                    $('.wbslv4').html(result);
                    console.log(result);
                }
            })
        }
    });

    $('.doc_counter').change(function(){
        if($('.r_date') != ''){
            var parts =  document.getElementById("receive_date").value.split('/');
            var start_date = new Date(parts[2], parts[1] - 1, parts[0]);
            var target_date = new Date(parts[2], parts[1] - 1, parts[0]);   
            var addDate = 0;
            if($(this).val() == '7'){
                var toggle_date = new Date(parts[2], parts[1] - 1, parts[0]);  
                for(var i = 0; i < 6; i++){
                    if(toggle_date.getDay() == '0'){
                        i--;
                        addDate ++;
                    }
                    else{
                        addDate++;
                    }
                    toggle_date.setDate(toggle_date.getDate()+1);
                    console.log(toggle_date.getDay() + ' addDate[' + addDate +']');
                }
                target_date.setDate(start_date.getDate() + addDate);
            }
            else{
                var toggle_date = new Date(parts[2], parts[1] - 1, parts[0]);  
                for(var i = 0; i < 13; i++){
                    if(toggle_date.getDay() == '0'){
                        i--;
                        addDate ++;
                    }
                    else{
                        addDate++;
                    }
                    toggle_date.setDate(toggle_date.getDate()+1);
                    console.log(toggle_date.getDay() + ' addDate[' + addDate +']');
                }
                target_date.setDate(start_date.getDate() + addDate);
            }
            var dd = target_date.getDate();
            var mm = ("0" + (target_date.getMonth() + 1)).slice(-2)
            var y = target_date.getFullYear();

            var format_target_date = dd + '/' + mm + '/' + y;
            document.getElementById("target_date").value = format_target_date;
           
        }
    });

    $('.r_date').click(function(){
        document.getElementById("target_date").value = null;
        document.getElementById("target_date").setText = null;
        $('.doc_counter').prop('selectedIndex', 0);

//        console.log('click');
    });
    
$("#ApproveAll").click(function () {
    var ident = '1';
    $(".review_status").val(ident).prop("selected", true).change();
    $("#AsNoteAll").prop("checked", false);
    $("#ResubmitAll").prop("checked", false);
    $("#RejectAll").prop("checked", false);
});

$("#AsNoteAll").click(function () {
    var ident = '2';
    $(".review_status").val(ident).prop("selected", true).change();
    $("#ApproveAll").prop("checked", false);
    $("#ResubmitAll").prop("checked", false);
    $("#RejectAll").prop("checked", false);
});

$("#ResubmitAll").click(function () {
    var ident = '3';
    $(".review_status").val(ident).prop("selected", true).change();
    $("#AsNoteAll").prop("checked", false);
    $("#ApproveAll").prop("checked", false);
    $("#RejectAll").prop("checked", false);
});

$("#RejectAll").click(function () {
    var ident = '4';
    $(".review_status").val(ident).prop("selected", true).change();
    $("#AsNoteAll").prop("checked", false);
    $("#ResubmitAll").prop("checked", false);
    $("#ApproveAll").prop("checked", false);
});

const getTwoDigits = (value) => value < 10 ? `0${value}` : value;

const formatDate = (date) => {
  const day = getTwoDigits(date.getDate());
  const month = getTwoDigits(date.getMonth() + 1); // add 1 since getMonth returns 0-11 for the months
  const year = date.getFullYear();

  return `${year}-${month}-${day}`;
}

const formatTime = (date) => {
  const hours = getTwoDigits(date.getHours());
  const mins = getTwoDigits(date.getMinutes());

  return `${hours}:${mins}`;
}

const date = new Date();
$(".date_returned").val(formatDate(date));

function check_stamp() {
        
          var str = "\"CONFIRM\"";
            swal({
                title: "{{ trans('global.change_box') }}",
                text: "{{ trans('global.please_enter') }}" + str + "{{ trans('global.to_confirm') }}",
                  type: "input",
                  showCancelButton: true,
                  closeOnConfirm: false,
                  inputPlaceholder: "CONFIRM"
              },
                  function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    if (inputValue == "CONFIRM") {
                        if(document.getElementById("cec_stamp_2").checked == false){
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.stamp_to_form') }}",
                                type : "success",
                            });
                        }
                        else{
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.unstamp_to_form') }}",
                                type : "success",
                            });
                        }
                    }
                    else{
                        swal.showInputError("{{ trans('global.invalid_box') }}");
                        if(document.getElementById("cec_stamp_2").checked == false){
                            document.getElementById("cec_stamp_2").checked = true;
                            document.getElementById("cec_stamp_1").checked = false;
                        }
                    else{
                        document.getElementById("cec_stamp_1").checked = true;
                        document.getElementById("cec_stamp_2").checked = false;
                    }
                  }
                    return false
                });
        }


        function check_sign() {
            var str = "\"CONFIRM\"";
            swal({
                title: "{{ trans('global.change_box') }}",
                text: "{{ trans('global.please_enter') }}" + str + "{{ trans('global.to_confirm') }}",
                  type: "input",
                  showCancelButton: true,
                  closeOnConfirm: false,
                  inputPlaceholder: "CONFIRM"
              },
                  function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    if (inputValue == "CONFIRM") {
                        if(document.getElementById("cec_sign_2").checked == false){
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.sign_to_form') }}",
                                type : "success",
                            });
                        }
                        else{
                            swal({
                                title: "{{ trans('global.confirm_success') }}",
                                text: "{{ trans('global.unsign_to_form') }}",
                                type : "success",
                            });
                        }
                    }
                    else{
                        swal.showInputError("{{ trans('global.invalid_box') }}");
                        
                        if(document.getElementById("cec_sign_2").checked == false){
                                document.getElementById("cec_sign_2").checked = true;
                                document.getElementById("cec_sign_1").checked = false;
                            }
                        else{
                                document.getElementById("cec_sign_1").checked = true;
                                document.getElementById("cec_sign_2").checked = false;
                            }
                      }
                      return false
                    });
        }


        $("#save_form").on('click',function(e) {
    
                event.preventDefault();

                swal({
                  title: "{{ trans('global.are_you_sure') }}",
                  text: "{{ trans('global.verify_form') }}",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#4BB543",
                  confirmButtonText: "{{ trans('global.yes_add') }}",
                  cancelButtonText: "{{ trans('global.no_cancel') }}",
                  closeOnConfirm: false,
                  closeOnCancel: false
                },
            function(isConfirm){
              if (isConfirm) {
                   // $('.swa-confirm').attr('data-flag', '1');
                    $('.swa-confirm').submit();
                  } else {
                swal("{{ trans('global.cancelled') }}", "{{trans('global.add_fail')}}", "error");  
              }
            });
        });



</script>


@endsection