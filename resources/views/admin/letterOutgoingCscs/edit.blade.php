@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.letterOutgoingCsc.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.letter-outgoing-cscs.update", [$letterOutgoingCsc->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('letter') ? 'has-error' : '' }}">
                            <label for="letter_id">{{ trans('cruds.letterOutgoingCsc.fields.letter') }}</label>
                            <select class="form-control select2" name="letter_id" id="letter_id">
                                @foreach($letters as $id => $letter)
                                    <option value="{{ $id }}" {{ ($letterOutgoingCsc->letter ? $letterOutgoingCsc->letter->id : old('letter_id')) == $id ? 'selected' : '' }}>{{ $letter }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('letter'))
                                <span class="help-block" role="alert">{{ $errors->first('letter') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.letterOutgoingCsc.fields.letter_helper') }}</span>
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