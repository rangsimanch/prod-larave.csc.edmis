@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.submittalsRfa.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.submittals-rfas.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('item_no') ? 'has-error' : '' }}">
                            <label for="item_no">{{ trans('cruds.submittalsRfa.fields.item_no') }}</label>
                            <input class="form-control" type="text" name="item_no" id="item_no" value="{{ old('item_no', '') }}">
                            @if($errors->has('item_no'))
                                <span class="help-block" role="alert">{{ $errors->first('item_no') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.submittalsRfa.fields.item_no_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.submittalsRfa.fields.description') }}</label>
                            <input class="form-control" type="text" name="description" id="description" value="{{ old('description', '') }}">
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.submittalsRfa.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('qty_sets') ? 'has-error' : '' }}">
                            <label for="qty_sets">{{ trans('cruds.submittalsRfa.fields.qty_sets') }}</label>
                            <input class="form-control" type="number" name="qty_sets" id="qty_sets" value="{{ old('qty_sets', '0') }}" step="1">
                            @if($errors->has('qty_sets'))
                                <span class="help-block" role="alert">{{ $errors->first('qty_sets') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.submittalsRfa.fields.qty_sets_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('review_status') ? 'has-error' : '' }}">
                            <label for="review_status_id">{{ trans('cruds.submittalsRfa.fields.review_status') }}</label>
                            <select class="form-control select2" name="review_status_id" id="review_status_id">
                                @foreach($review_statuses as $id => $entry)
                                    <option value="{{ $id }}" {{ old('review_status_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('review_status'))
                                <span class="help-block" role="alert">{{ $errors->first('review_status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.submittalsRfa.fields.review_status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('date_returned') ? 'has-error' : '' }}">
                            <label for="date_returned">{{ trans('cruds.submittalsRfa.fields.date_returned') }}</label>
                            <input class="form-control date" type="text" name="date_returned" id="date_returned" value="{{ old('date_returned') }}">
                            @if($errors->has('date_returned'))
                                <span class="help-block" role="alert">{{ $errors->first('date_returned') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.submittalsRfa.fields.date_returned_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                            <label for="remarks">{{ trans('cruds.submittalsRfa.fields.remarks') }}</label>
                            <textarea class="form-control" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                            @if($errors->has('remarks'))
                                <span class="help-block" role="alert">{{ $errors->first('remarks') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.submittalsRfa.fields.remarks_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('on_rfa') ? 'has-error' : '' }}">
                            <label for="on_rfa_id">{{ trans('cruds.submittalsRfa.fields.on_rfa') }}</label>
                            <select class="form-control select2" name="on_rfa_id" id="on_rfa_id">
                                @foreach($on_rfas as $id => $entry)
                                    <option value="{{ $id }}" {{ old('on_rfa_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('on_rfa'))
                                <span class="help-block" role="alert">{{ $errors->first('on_rfa') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.submittalsRfa.fields.on_rfa_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
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