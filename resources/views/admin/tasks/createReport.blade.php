@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.task.title_singular') }}
                </div>
                <form method="POST" action="{{ route("admin.tasks.createReportTask") }}" enctype="multipart/form-data">  
                @csrf
                    <div class="panel-body">

                        <div class="form-group {{ $errors->has('create_by_user') ? 'has-error' : '' }}">
                                    <label for="create_by_user_id" class="required"> User </label>
                                    <select class="form-control select2" name="create_by_user_id" id="create_by_user_id">
                                        @foreach($create_by_users as $id => $create_by_user)
                                            <option value="{{ $id }}" {{ old('create_by_user_id') == $id ? 'selected' : '' }}>{{ $create_by_user }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('create_by_user'))
                                        <span class="help-block" role="alert">{{ $errors->first('create_by_user') }}</span>
                                    @endif
                        </div>

                        <div class="form-group {{ $errors->has('contracts') ? 'has-error' : '' }}">
                                    <label for="contracts" class="required"> Contract </label>
                                    <select class="form-control select2" name="contracts" id="contracts">
                                        @foreach($contracts as $id => $contract)
                                            <option value="{{ $id }}">{{ $contract }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('contracts'))
                                        <span class="help-block" role="alert">{{ $errors->first('contracts') }}</span>
                                    @endif
                        </div>
                        
                        <div class="form-group">
                            <div class="form-group col">
                                <label for="starttDate" class="required">Start Date</label>
                                <input type="text" class="form-control date" id="startDate" name="startDate" value="{{ old('startDate') }}">
                            </div>
                            <div class="form-group">
                                <label for="endDate" class="required">End Date</label>
                                <input type="text" class="form-control date" id="endDate" name="endDate" value="{{ old('endDate') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reportType" class="required">Report Type</label>
                            <select id="reportType" name="reportType" class="form-control select2" required>
                                <option selected value="Daily Report">Daily</option>
                                <option value="Weekly Report">Weekly</option>
                                <option value="Monthly Report">Monthly</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success" type="submit" id="demo">
                                Create Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<div class="loader-wrapper">
    <span class="loader"><span class="loader-inner"></span></span>
</div>


@endsection

@section('scripts')
<script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }

    $(window).on("load",function(){
          $(".loader-wrapper").fadeOut("slow");
    });

    document.getElementById("demo").onclick = function() {myFunction()};

    function myFunction() {
        $(window).on("load",function(){
          $(".loader-wrapper").fadeOut("slow");
        });
    }
  </script>

  
  @endsection