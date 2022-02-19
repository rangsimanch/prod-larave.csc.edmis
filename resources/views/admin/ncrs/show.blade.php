@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.ncr.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.ncrs.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.construction_contract') }}
                                    </th>
                                    <td>
                                        {{ $ncr->construction_contract->code ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.corresponding_ncn') }}
                                    </th>
                                    <td>
                                        {{ $ncr->corresponding_ncn->document_number ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.document_number') }}
                                    </th>
                                    <td>
                                        {{ $ncr->document_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.acceptance_date') }}
                                    </th>
                                    <td>
                                        {{ $ncr->acceptance_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.attachment_description') }}
                                    </th>
                                    <td>
                                        {!! $ncr->attachment_description !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.pages_of_attachment') }}
                                    </th>
                                    <td>
                                        {{ $ncr->pages_of_attachment }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.file_attachment') }}
                                    </th>
                                    <td>
                                        @foreach($ncr->file_attachment as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.prepared_by') }}
                                    </th>
                                    <td>
                                        {{ $ncr->prepared_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.documents_status') }}
                                    </th>
                                    <td>
                                        {{ App\Ncr::DOCUMENTS_STATUS_SELECT[$ncr->documents_status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.construction_specialist') }}
                                    </th>
                                    <td>
                                        {{ $ncr->construction_specialist->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.ncr.fields.leader') }}
                                    </th>
                                    <td>
                                        {{ $ncr->leader->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.ncrs.index') }}">
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