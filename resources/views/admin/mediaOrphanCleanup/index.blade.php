@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Media Orphan Cleanup</strong>
                    <span class="pull-right text-muted small">
                        ตรวจหา media ที่ parent model ถูก delete แล้ว เพื่อพิจารณาลบถาวร
                    </span>
                </div>
                <div class="panel-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($warning)
                        <div class="alert alert-warning">{{ $warning }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin:0;">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.media-orphan-cleanup.index') }}" class="form-inline" style="margin-bottom: 15px;">
                        <input type="hidden" name="submitted" value="1">

                        <div class="form-group" style="margin-right: 10px;">
                            <label>Model Type</label><br>
                            <select name="model_type" class="form-control">
                                <option value="">-- เลือก model --</option>
                                @foreach($modelTypeOptions as $class)
                                    <option value="{{ $class }}" @if(($input['model_type'] ?? null) === $class) selected @endif>{{ class_basename($class) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label>Status</label><br>
                            <select name="status" class="form-control">
                                <option value="orphan" @if(($input['status'] ?? null) === 'orphan') selected @endif>orphan (soft_deleted + missing)</option>
                                <option value="soft_deleted" @if(($input['status'] ?? null) === 'soft_deleted') selected @endif>soft_deleted</option>
                                <option value="missing" @if(($input['status'] ?? null) === 'missing') selected @endif>missing (parent หายไป)</option>
                                <option value="stale_class" @if(($input['status'] ?? null) === 'stale_class') selected @endif>stale_class (model_type ไม่มีในโค้ด)</option>
                                <option value="active" @if(($input['status'] ?? null) === 'active') selected @endif>active (ห้ามลบ)</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label>Deleted From</label><br>
                            <input type="date" name="deleted_from" class="form-control" value="{{ $input['deleted_from'] ?? '' }}">
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label>Deleted To</label><br>
                            <input type="date" name="deleted_to" class="form-control" value="{{ $input['deleted_to'] ?? '' }}">
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label>Min Size (bytes)</label><br>
                            <input type="number" name="min_size" class="form-control" value="{{ $input['min_size'] ?? '' }}" placeholder="เช่น 1048576">
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label>Max Size (bytes)</label><br>
                            <input type="number" name="max_size" class="form-control" value="{{ $input['max_size'] ?? '' }}">
                        </div>

                        <div class="form-group" style="margin-right: 10px;">
                            <label>Sort</label><br>
                            <select name="sort" class="form-control">
                                <option value="id_desc" @if(($input['sort'] ?? 'id_desc') === 'id_desc') selected @endif>id (newest)</option>
                                <option value="size_desc" @if(($input['sort'] ?? null) === 'size_desc') selected @endif>size (largest)</option>
                                <option value="size_asc" @if(($input['sort'] ?? null) === 'size_asc') selected @endif>size (smallest)</option>
                                <option value="days_desc" @if(($input['sort'] ?? null) === 'days_desc') selected @endif>days since delete (oldest)</option>
                                <option value="days_asc" @if(($input['sort'] ?? null) === 'days_asc') selected @endif>days since delete (newest)</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('admin.media-orphan-cleanup.index') }}" class="btn btn-default">Reset</a>
                        </div>
                    </form>

                    @if($needsMoreFilter)
                        <div class="alert alert-info">
                            กรุณาเพิ่ม filter ช่วงวันที่ (Deleted From/To) หรือขนาดไฟล์ (Min/Max Size) เพื่อลด scope ของผลลัพธ์
                        </div>
                    @elseif(isset($results))
                        <hr>
                        <p class="text-muted">ผลลัพธ์ทั้งหมด: <strong>{{ number_format($totalResults) }}</strong> rows (แสดงหน้าละ 50)</p>

                        @can('media_orphan_cleanup_force_delete')
                            <form method="POST" action="{{ URL::temporarySignedRoute('admin.media-orphan-cleanup.massDestroy', now()->addMinutes(5)) }}" id="massDestroyForm" style="margin-bottom: 10px;">
                                @csrf
                                <button type="button" class="btn btn-danger btn-sm" id="massDestroyBtn" disabled>
                                    ลบที่เลือก (mass)
                                </button>
                                <span class="text-muted small">— mass delete ไม่รองรับ stale_class (ต้องลบทีละ row ผ่านหน้า detail)</span>
                            </form>
                        @else
                            <div class="alert alert-info small" style="margin-bottom: 10px;">
                                คุณมีสิทธิ์เฉพาะการดู — ต้องมี permission <code>media_orphan_cleanup_force_delete</code> เพื่อลบ
                            </div>
                        @endcan

                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="20"><input type="checkbox" id="selectAll"></th>
                                    <th>ID</th>
                                    <th>Model</th>
                                    <th>Model ID</th>
                                    <th>Collection</th>
                                    <th>File Name</th>
                                    <th>Size</th>
                                    <th>Disk</th>
                                    <th>Status</th>
                                    <th>File on Disk</th>
                                    <th>Deleted At</th>
                                    <th>Days</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $validModelTypes = $validModelTypes;
                                @endphp
                                @foreach($results as $row)
                                    @php
                                        $parentTable = $validModelTypes[$row->model_type] ?? null;
                                        $parentDeletedAt = $parentTable ? DB::table($parentTable)->where('id', $row->model_id)->value('deleted_at') : null;
                                        $isStaleClass = !array_key_exists($row->model_type, $validModelTypes);
                                        $parentExists = $parentTable ? DB::table($parentTable)->where('id', $row->model_id)->exists() : false;
                                        if ($isStaleClass) {
                                            $status = 'stale_class';
                                        } elseif (!$parentExists) {
                                            $status = 'missing';
                                        } elseif ($parentDeletedAt) {
                                            $status = 'soft_deleted';
                                        } else {
                                            $status = 'active';
                                        }
                                        $days = $parentDeletedAt ? \Carbon\Carbon::parse($parentDeletedAt)->diffInDays(now()) : null;
                                        $fileOnDisk = \Illuminate\Support\Facades\Storage::disk($row->disk)->exists($row->id . '/' . $row->file_name);
                                        $canDelete = in_array($status, ['soft_deleted', 'missing', 'stale_class']);
                                    @endphp
                                    <tr>
                                        <td>
                                            @can('media_orphan_cleanup_force_delete')
                                                @if($canDelete && $status !== 'stale_class')
                                                    <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="rowCheckbox" form="massDestroyForm">
                                                @endif
                                            @endcan
                                        </td>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ class_basename($row->model_type) }}</td>
                                        <td>{{ $row->model_id }}</td>
                                        <td>{{ $row->collection_name }}</td>
                                        <td>{{ $row->file_name }}</td>
                                        <td>{{ number_format($row->size / 1048576, 2) }} MB</td>
                                        <td>{{ $row->disk }}</td>
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
                                        <td>
                                            @if($fileOnDisk)
                                                <span class="label label-default">ใช่</span>
                                            @else
                                                <span class="label label-danger" title="ไฟล์หายไปจาก disk (อาจถูกลบโดย attacker)">หาย</span>
                                            @endif
                                        </td>
                                        <td>{{ $parentDeletedAt ?? '-' }}</td>
                                        <td>{{ $days ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.media-orphan-cleanup.show', $row->id) }}" class="btn btn-xs btn-info">ดูรายละเอียด</a>
                                            <a href="{{ route('admin.media-orphan-cleanup.preview', $row->id) }}" target="_blank" class="btn btn-xs btn-default">Preview</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $results->links() }}

                        <script>
                            document.getElementById('selectAll').addEventListener('change', function() {
                                document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = this.checked);
                                updateMassBtn();
                            });
                            document.querySelectorAll('.rowCheckbox').forEach(cb => cb.addEventListener('change', updateMassBtn));
                            function updateMassBtn() {
                                const checked = document.querySelectorAll('.rowCheckbox:checked').length;
                                document.getElementById('massDestroyBtn').disabled = checked === 0;
                            }
                        </script>
                    @else
                        <div class="alert alert-info">
                            เลือก filter ด้านบนแล้วกด Search เพื่อเริ่มตรวจสอบ — หน้านี้ไม่โหลดข้อมูลอัตโนมัติเพื่อประหยัดทรัพยากร
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Mass destroy: prompt for password + acknowledged before submit
    document.getElementById('massDestroyBtn')?.addEventListener('click', function() {
        const checked = document.querySelectorAll('.rowCheckbox:checked');
        if (checked.length === 0) return;
        if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ ' + checked.length + ' media rows ถาวร?\n\nการลบเป็นถาวร แม้ model จะถูก restore ภายหลัง ไฟล์จะไม่กลับมา')) {
            return;
        }
        const password = prompt('กรอก password ของคุณเพื่อยืนยันการลบ:');
        if (!password) return;

        // Add hidden inputs to the form
        const form = document.getElementById('massDestroyForm');
        const pwInput = document.createElement('input');
        pwInput.type = 'hidden';
        pwInput.name = 'confirm_password';
        pwInput.value = password;
        form.appendChild(pwInput);

        const ackInput = document.createElement('input');
        ackInput.type = 'hidden';
        ackInput.name = 'acknowledged';
        ackInput.value = '1';
        form.appendChild(ackInput);

        form.submit();
    });
</script>
@endsection
