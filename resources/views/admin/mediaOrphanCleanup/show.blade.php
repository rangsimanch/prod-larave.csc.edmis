@extends('layouts.admin')

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

                        <form method="POST" action="{{ $deleteAction }}" id="deleteForm">
                            @csrf

                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <strong>Step 1: รายละเอียดยืนยัน</strong>
                                </div>
                                <div class="panel-body">
                                    <p>
                                        กำลังจะลบ: <code>{{ $media->file_name }}</code> ({{ $humanSize }})<br>
                                        ของ model: <code>{{ $media->model_type }}</code> #{{ $media->model_id }}
                                    </p>
                                    <div class="alert alert-danger">
                                        <strong>คำเตือน:</strong> การลบเป็นถาวร — แม้ model จะถูก restore ภายหลัง ไฟล์จะไม่กลับมา
                                        @if(!$fileOnDisk)
                                            <br>(ไฟล์ใน disk หายไปแล้ว — การลบจะลบเฉพาะ media row ใน DB)
                                        @endif
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="acknowledged" value="1" required id="ackCheckbox">
                                            ฉันเข้าใจว่าการลบเป็นถาวรและยืนยันว่าได้ตรวจสอบรายละเอียดข้างต้นแล้ว
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <strong>Step 2: กรอก password เพื่อยืนยัน</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>Password ของคุณ</label>
                                        <input type="password" name="confirm_password" class="form-control" required id="pwInput">
                                    </div>
                                    @if($status === 'stale_class')
                                        <div class="form-group">
                                            <label>เหตุผลในการลบ (required สำหรับ stale_class)</label>
                                            <textarea name="reason" class="form-control" rows="2" required placeholder="เช่น: model นี้ถูกลบออกจากระบบแล้ว ไม่มีการใช้งาน"></textarea>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label>เหตุผล (optional)</label>
                                            <textarea name="reason" class="form-control" rows="2" placeholder="บันทึกเหตุผลเพิ่มเติม (ถ้ามี)"></textarea>
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                                        ลบถาวร
                                    </button>
                                </div>
                            </div>
                        </form>

                        <script>
                            const ack = document.getElementById('ackCheckbox');
                            const pw = document.getElementById('pwInput');
                            const btn = document.getElementById('deleteBtn');
                            function updateBtn() {
                                btn.disabled = !(ack.checked && pw.value.length > 0);
                            }
                            ack.addEventListener('change', updateBtn);
                            pw.addEventListener('input', updateBtn);

                            document.getElementById('deleteForm').addEventListener('submit', function(e) {
                                if (!confirm('ยืนยันการลบถาวรครั้งสุดท้าย?\n\nไฟล์: {{ addslashes($media->file_name) }}\nModel: {{ addslashes($media->model_type) }} #{{ $media->model_id }}\n\nการลบเป็นถาวร ไม่สามารถกู้คืนได้')) {
                                    e.preventDefault();
                                }
                            });
                        </script>
                    @endif
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
