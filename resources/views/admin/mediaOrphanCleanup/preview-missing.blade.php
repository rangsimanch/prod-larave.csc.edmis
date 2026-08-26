@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <strong>ไฟล์หายไปจาก disk — Media #{{ $media->id }}</strong>
                </div>
                <div class="panel-body">
                    <div class="alert alert-warning">
                        <strong>ไม่สามารถ preview ไฟล์นี้ได้</strong><br>
                        ไฟล์หายไปจาก disk แล้ว (อาจถูกลบโดย attacker ครั้งก่อน) —
                        แต่ media row ยังคงอยู่ใน DB และสามารถลบได้เพื่อ cleanup
                    </div>

                    <table class="table table-bordered">
                        <tbody>
                            <tr><th width="200">Media ID</th><td>{{ $media->id }}</td></tr>
                            <tr><th>File Name</th><td>{{ $media->file_name }}</td></tr>
                            <tr><th>Model Type</th><td>{{ $media->model_type }}</td></tr>
                            <tr><th>Model ID</th><td>{{ $media->model_id }}</td></tr>
                            <tr><th>Disk</th><td>{{ $media->disk }}</td></tr>
                        </tbody>
                    </table>

                    <a href="{{ route('admin.media-orphan-cleanup.show', $media->id) }}" class="btn btn-primary">
                        ไปหน้ารายละเอียดเพื่อลบ media row
                    </a>
                    <a href="{{ route('admin.media-orphan-cleanup.index') }}" class="btn btn-default">กลับไปยังรายการ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
