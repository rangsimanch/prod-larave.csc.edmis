@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.addLetter.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.add-letters.update", [$addLetter->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        
                        <div class="form-group {{ $errors->has('mask_as_received') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="mask_as_received" value="0">
                                <input type="checkbox" name="mask_as_received" onclick="EnableDisableTextBox(this)" id="mask_as_received" value="1" {{ $addLetter->mask_as_received || old('mask_as_received', 0) === 1 ? 'checked' : '' }}>
                                <label for="mask_as_received" style="font-weight: 400">{{ trans('cruds.addLetter.fields.mask_as_received') }}</label>
                            </div>
                            @if($errors->has('mask_as_received'))
                                <span class="help-block" role="alert">{{ $errors->first('mask_as_received') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.mask_as_received_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('received_date') ? 'has-error' : '' }}">
                            <label for="received_date">{{ trans('cruds.addLetter.fields.received_date') }}</label>
                            <input class="form-control date" type="text" name="received_date" id="received_date" disabled="disabled" value="{{ old('received_date', $addLetter->received_date) }}">
                            @if($errors->has('received_date'))
                                <span class="help-block" role="alert">{{ $errors->first('received_date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.received_date_helper') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                            <label for="note">{{ trans('cruds.addLetter.fields.note') }}</label>
                            <textarea class="form-control" name="note" id="note">{{ old('note', $addLetter->note) }}</textarea>
                            @if($errors->has('note'))
                                <span class="help-block" role="alert">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addLetter.fields.note_helper') }}</span>
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

<script type="text/javascript">
    function EnableDisableTextBox(mask_as_received) {
        var received_date = document.getElementById("received_date");
        received_date.disabled = mask_as_received.checked ? false : true;
        if (!received_date.disabled) {
            received_date.focus();
        }
        else{
            document.getElementById("received_date").value = "";
        }
    }
</script>
@endsection

