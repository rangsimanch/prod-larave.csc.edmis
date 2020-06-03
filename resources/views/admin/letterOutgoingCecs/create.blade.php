@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.letterOutgoingCec.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.letter-outgoing-cecs.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('letter') ? 'has-error' : '' }}">
                            <label for="letter_id">{{ trans('cruds.letterOutgoingCec.fields.letter') }}</label>
                            <select class="form-control select2" name="letter_id" id="letter_id">
                                @foreach($letters as $id => $letter)
                                    <option value="{{ $id }}" {{ old('letter_id') == $id ? 'selected' : '' }}>{{ $letter }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('letter'))
                                <span class="help-block" role="alert">{{ $errors->first('letter') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.letterOutgoingCec.fields.letter_helper') }}</span>
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