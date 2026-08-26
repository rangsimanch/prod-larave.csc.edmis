@extends('layouts.admin')

@section('styles')
<style>
    .modal-header-danger {
        background-color: #d9534f;
        color: #fff;
    }
    .modal-header-danger .close {
        color: #fff;
        opacity: 0.8;
    }
    .modal-header-danger .close:hover {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Media Detail #{{ $media->id }}</strong>
                    <a href="{{ route('admin.media-orphan-cleanup.index') }}" class="btn btn-default btn-sm pull-right">กลับไปยังรายการ</a>
                </div>
                <div class="panel-body">

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <h4>Media Information</h4>
                    <table class="table table-bordered">
                        <tbody>
                            <tr><th width="200">ID</th><td>{{ $media->id }}</td></tr>
                            <tr><th>Model Type</th><td>{{ $media->model_type }}</td></tr>
                            <tr><th>Model ID</th><td>{{ $media->model_id }}</td></tr>
                            <tr><th>Collection</th><td>{{ $media->collection_name }}</td></tr>
                            <tr><th>Name</th><td>{{ $media->name }}</td></tr>
                            <tr><th>File Name</th><td>{{ $media->file_name }}</td></tr>
                            <tr><th>MIME Type</th><td>{{ $media->mime_type ?? '-' }}</td></tr>
                            <tr><th>Disk</th><td>{{ $media->disk }}</td></tr>
                            <tr><th>Size</th><td>{{ $humanSize }} ({{ number_format($media->size) }} bytes)</td></tr>
                            <tr><th>Created At</th><td>{{ $media->created_at }}</td></tr>
                            <tr><th>Order Column</th><td>{{ $media->order_column ?? '-' }}</td></tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($status === 'active')
                                        <span class="label label-success">active</span>
                                    @elseif($status === 'soft_deleted')
                                        <span class="label label-warning">soft_deleted</span>
                                    @elseif($status === 'missing')
                                        <span class="label label-danger">missing</span>
                                    @elseif($status === 'stale_class')
                                        <span class="label label-danger">stale_class</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>File on Disk</th>
                                <td>
                                    @if($fileOnDisk)
                                        <span class="label label-default">ใช่ — ไฟล์ยังอยู่</span>
                                    @else
                                        <span class="label label-danger">หายไปจาก disk</span>
                                        <div class="text-muted small" style="margin-top: 5px;">
                                            ไฟล์นี้หายไปจาก disk แล้ว (อาจถูกลบโดย attacker ครั้งก่อน) — การลบจะลบเฉพาะ media row ใน DB
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Preview</th>
                                <td>
                                    <a href="{{ route('admin.media-orphan-cleanup.preview', $media->id) }}" target="_blank" class="btn btn-default btn-sm">
                                        เปิดดูไฟล์
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>

                    <h4>Parent Model Detail</h4>
                    @if($status === 'stale_class')
                        <div class="alert alert-danger">
                            <strong>model_type นี้ไม่มีในโค้ดปัจจุบัน:</strong> {{ $media->model_type }}<br>
                            ตรวจสอบให้แน่ใจว่าไม่ใช่ model ที่ถูก refactor/เปลี่ยน namespace ก่อนลบ —
                            ถ้าเคย refactor ควร update <code>media.model_type</code> ด้วย migration manual แทนการลบ
                        </div>
                    @elseif($status === 'missing')
                        <div class="alert alert-warning">
                            ข้อมูล model หายไปจากฐานข้อมูล (force-deleted หรือไม่เคยมี) — ไม่สามารถแสดงรายละเอียด parent ได้
                        </div>
                    @elseif($parentAttributes)
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr><th>Column</th><th>Value</th></tr>
                            </thead>
                            <tbody>
                                @foreach($parentAttributes as $col => $val)
                                    <tr>
                                        <th>{{ $col }}</th>
                                        <td>
                                            @if(is_array($val))
                                                <code>{{ json_encode($val) }}</code>
                                            @else
                                                {{ $val ?? '(null)' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($status === 'soft_deleted')
                            <p>
                                <strong>Deleted At:</strong> {{ $parent->deleted_at }}<br>
                                <strong>Days Since Delete:</strong> {{ $daysSinceDelete }} วัน
                            </p>
                        @endif
                    @endif

                    <hr>

                    <h4>Permanent Delete</h4>

                    @cannot('media_orphan_cleanup_force_delete')
                        <div class="alert alert-info">
                            <strong>คุณมีสิทธิ์เฉพาะการดู</strong> — ต้องมี permission <code>media_orphan_cleanup_force_delete</code> เพื่อลบ
                        </div>
                    @else
                        @if(!$canDelete)
                            <div class="alert alert-danger">
                                <strong>ไม่สามารถลบได้:</strong> model ยัง active อยู่ (parent ยังไม่ถูก soft-delete) —
                                ระบบปฏิเสธการลบไฟล์ของ model ที่ยังใช้งานอยู่เด็ดขาด
                            </div>
                        @else
                            @if($status === 'stale_class')
                                <div class="alert alert-warning">
                                    <strong>กรณี stale_class:</strong> ต้องกรอกเหตุผลก่อนลบ (เพื่อบันทึกใน audit log ว่าทำไมจึงลบ)
                                </div>
                            @endif

                            <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#deleteConfirmModal">
                                <i class="fa fa-trash"></i> เปิดหน้าต่างยืนยันการลบถาวร
                            </button>

                            <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="deleteModalTitle">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ $deleteAction }}" id="deleteForm">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header modal-header-danger">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="deleteModalTitle">
                                                    <i class="fa fa-exclamation-triangle"></i> ยืนยันการลบถาวร — Media #{{ $media->id }}
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-danger">
                                                    <strong>คำเตือน:</strong> การลบเป็นถาวร — แม้ model จะถูก restore ภายหลัง ไฟล์จะไม่กลับมา
                                                    @if(!$fileOnDisk)
                                                        <br>(ไฟล์ใน disk หายไปแล้ว — การลบจะลบเฉพาะ media row ใน DB)
                                                    @endif
                                                </div>

                                                <table class="table table-bordered table-condensed">
                                                    <tbody>
                                                        <tr><th width="180">File Name</th><td><code>{{ $media->file_name }}</code></td></tr>
                                                        <tr><th>Size</th><td>{{ $humanSize }}</td></tr>
                                                        <tr><th>Model Type</th><td><code>{{ $media->model_type }}</code></td></tr>
                                                        <tr><th>Model ID</th><td>{{ $media->model_id }}</td></tr>
                                                        <tr><th>Status</th><td>
                                                            @if($status === 'soft_deleted')
                                                                <span class="label label-warning">soft_deleted</span>
                                                            @elseif($status === 'missing')
                                                                <span class="label label-danger">missing</span>
                                                            @elseif($status === 'stale_class')
                                                                <span class="label label-danger">stale_class</span>
                                                            @endif
                                                        </td></tr>
                                                    </tbody>
                                                </table>

                                                <hr>

                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label style="font-weight: bold;">
                                                            <input type="checkbox" name="acknowledged" value="1" required id="ackCheckbox">
                                                            ฉันเข้าใจว่าการลบเป็นถาวร และยืนยันว่าได้ตรวจสอบรายละเอียดข้างต้นแล้ว
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="pwInput">Password ของคุณ <span class="text-danger">*</span></label>
                                                    <input type="password" name="confirm_password" class="form-control" required id="pwInput" autocomplete="current-password">
                                                    <p class="help-block text-muted small">ระบบจะตรวจสอบ password ก่อนลบ และบันทึก audit log ทุกครั้ง</p>
                                                </div>

                                                @if($status === 'stale_class')
                                                    <div class="form-group">
                                                        <label for="reasonInput">เหตุผลในการลบ <span class="text-danger">*</span> (required สำหรับ stale_class)</label>
                                                        <textarea name="reason" class="form-control" rows="2" required id="reasonInput" placeholder="เช่น: model นี้ถูกลบออกจากระบบแล้ว ไม่มีการใช้งาน"></textarea>
                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <label for="reasonInput">เหตุผล (optional)</label>
                                                        <textarea name="reason" class="form-control" rows="2" id="reasonInput" placeholder="บันทึกเหตุผลเพิ่มเติม (ถ้ามี)"></textarea>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                                                    <i class="fa fa-trash"></i> ยืนยันลบถาวร
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                                (function() {
                                    const ack = document.getElementById('ackCheckbox');
                                    const pw = document.getElementById('pwInput');
                                    const btn = document.getElementById('deleteBtn');
                                    const reason = document.getElementById('reasonInput');
                                    const reasonRequired = {{ $status === 'stale_class' ? 'true' : 'false' }};

                                    function updateBtn() {
                                        let ok = ack.checked && pw.value.length > 0;
                                        if (reasonRequired && reason.value.trim().length === 0) {
                                            ok = false;
                                        }
                                        btn.disabled = !ok;
                                    }
                                    ack.addEventListener('change', updateBtn);
                                    pw.addEventListener('input', updateBtn);
                                    if (reason) reason.addEventListener('input', updateBtn);

                                    // Clear password on modal close (security)
                                    $('#deleteConfirmModal').on('hidden.bs.modal', function() {
                                        pw.value = '';
                                        ack.checked = false;
                                        if (reason) reason.value = '';
                                        updateBtn();
                                    });
                                })();
                            </script>
                        @endif
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
