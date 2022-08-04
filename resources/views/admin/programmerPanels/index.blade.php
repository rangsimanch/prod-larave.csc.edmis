@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.programmerPanel.title') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.programmer-panels.deleteFolder") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file_ID">File ID</label>
                            <textarea class="form-control" id="file_ID" name="file_ID" rows="3" disabled></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Confirm</button>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection