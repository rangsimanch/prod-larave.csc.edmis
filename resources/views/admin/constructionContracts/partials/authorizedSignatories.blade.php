<div class="panel panel-default">
    <div class="panel-heading">
        Authorized Signatories
        <span class="help-block" style="display:inline; margin-left:10px;">Signers selectable on RFA documents for this contract</span>
    </div>
    <div class="panel-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($constructionContract->authorizedSignatories as $signatory)
            <div class="panel panel-default" style="margin-bottom:10px;">
                <div class="panel-heading" style="overflow:hidden;">
                    <strong>{{ $signatory->name }}</strong>
                    @if($signatory->is_default)
                        <span class="label label-primary">Default</span>
                    @endif
                    @if(!$signatory->is_active)
                        <span class="label label-default">Inactive</span>
                    @endif
                    <span class="pull-right">
                        <button class="btn btn-xs btn-default" type="button" data-toggle="collapse" data-target="#signatory-{{ $signatory->id }}">Manage</button>
                        <form method="POST" action="{{ route('admin.authorized-signatories.destroy', $signatory->id) }}" style="display:inline;" onsubmit="return confirm('Delete this signatory and its job titles?');">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                        </form>
                    </span>
                </div>
                <div id="signatory-{{ $signatory->id }}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form method="POST" action="{{ route('admin.authorized-signatories.update', $signatory->id) }}" class="form-inline" enctype="multipart/form-data" style="margin-bottom:15px;">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label>Name</label><br>
                                <input class="form-control" type="text" name="name" value="{{ $signatory->name }}" required style="width:280px;">
                            </div>
                            <div class="form-group">
                                <label>Name (RFI)</label><br>
                                <input class="form-control" type="text" name="name_rfi" value="{{ $signatory->name_rfi }}" placeholder="optional - name printed on RFI" style="width:220px;">
                            </div>
                            <div class="form-group">
                                <label>Signature File</label><br>
                                <select class="form-control" name="signature_path" style="width:180px; display:inline-block;">
                                    <option value="">- none -</option>
                                    @foreach($signature_files as $file)
                                        <option value="{{ $file }}" {{ $signatory->signature_path == $file ? 'selected' : '' }}>{{ $file }}</option>
                                    @endforeach
                                </select>
                                <span class="sig-upload-wrap" style="display:inline-block;">
                                    <label class="btn btn-xs btn-default" style="margin-bottom:0;">
                                        Upload&hellip;
                                        <input type="file" name="signature_file" accept="image/*" style="display:none;">
                                    </label>
                                    <span class="text-muted small sig-upload-name"></span>
                                </span>
                            </div>
                            <div class="form-group">
                                <label>Size H/W</label><br>
                                <input class="form-control" type="number" name="signature_size_h" value="{{ $signatory->signature_size_h }}" style="width:70px; display:inline-block;">
                                <input class="form-control" type="number" name="signature_size_w" value="{{ $signatory->signature_size_w }}" style="width:70px; display:inline-block;">
                            </div>
                            <div class="form-group">
                                <label>Sort</label><br>
                                <input class="form-control" type="number" name="sort_order" value="{{ $signatory->sort_order }}" style="width:80px;">
                            </div>
                            <div class="checkbox"><label><input type="checkbox" name="is_default" value="1" {{ $signatory->is_default ? 'checked' : '' }}> Default</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="is_active" value="1" {{ $signatory->is_active ? 'checked' : '' }}> Active</label></div>
                            <button class="btn btn-xs btn-primary" type="submit">Save</button>
                        </form>

                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th style="min-width:240px;">Job Title</th>
                                    <th title="Signature position Top / Left">Pos T/L</th>
                                    <th title="Jobtitle / Issue / Issue-sub position">JT/Iss/Sub</th>
                                    <th>Sort</th>
                                    <th>Def</th>
                                    <th>Act</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($signatory->jobtitles as $jobtitle)
                                    <tr>
                                        <form method="POST" action="{{ route('admin.authorized-signatory-jobtitles.update', $jobtitle->id) }}">
                                            @method('PUT')
                                            @csrf
                                            <td><input class="form-control input-sm" type="text" name="jobtitle" value="{{ $jobtitle->jobtitle }}" required></td>
                                            <td>
                                                <input class="form-control input-sm" type="number" name="signature_position_top" value="{{ $jobtitle->signature_position_top }}" style="width:60px; display:inline-block;">
                                                <input class="form-control input-sm" type="number" name="signature_position_left" value="{{ $jobtitle->signature_position_left }}" style="width:60px; display:inline-block;">
                                            </td>
                                            <td>
                                                <input class="form-control input-sm" type="number" name="jobtitle_position_lf" value="{{ $jobtitle->jobtitle_position_lf }}" style="width:55px; display:inline-block;">
                                                <input class="form-control input-sm" type="number" name="issue_position_lf" value="{{ $jobtitle->issue_position_lf }}" style="width:55px; display:inline-block;">
                                                <input class="form-control input-sm" type="number" name="issue_position_lf_sub" value="{{ $jobtitle->issue_position_lf_sub }}" style="width:55px; display:inline-block;">
                                            </td>
                                            <td><input class="form-control input-sm" type="number" name="sort_order" value="{{ $jobtitle->sort_order }}" style="width:55px;"></td>
                                            <td class="text-center"><input type="checkbox" name="is_default" value="1" {{ $jobtitle->is_default ? 'checked' : '' }}></td>
                                            <td class="text-center"><input type="checkbox" name="is_active" value="1" {{ $jobtitle->is_active ? 'checked' : '' }}></td>
                                            <td style="white-space:nowrap;">
                                                <button class="btn btn-xs btn-primary" type="submit">Save</button>
                                        </form>
                                                <form method="POST" action="{{ route('admin.authorized-signatory-jobtitles.destroy', $jobtitle->id) }}" style="display:inline;" onsubmit="return confirm('Delete this job title?');">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button class="btn btn-xs btn-danger" type="submit">Del</button>
                                                </form>
                                            </td>
                                    </tr>
                                @endforeach
                                <tr style="background:#f5f5f5;">
                                    <form method="POST" action="{{ route('admin.authorized-signatories.jobtitles.store', $signatory->id) }}">
                                        @csrf
                                        <td><input class="form-control input-sm" type="text" name="jobtitle" placeholder="New job title" required></td>
                                        <td>
                                            <input class="form-control input-sm" type="number" name="signature_position_top" placeholder="T" style="width:60px; display:inline-block;">
                                            <input class="form-control input-sm" type="number" name="signature_position_left" placeholder="L" style="width:60px; display:inline-block;">
                                        </td>
                                        <td>
                                            <input class="form-control input-sm" type="number" name="jobtitle_position_lf" placeholder="JT" style="width:55px; display:inline-block;">
                                            <input class="form-control input-sm" type="number" name="issue_position_lf" placeholder="Iss" style="width:55px; display:inline-block;">
                                            <input class="form-control input-sm" type="number" name="issue_position_lf_sub" placeholder="Sub" style="width:55px; display:inline-block;">
                                        </td>
                                        <td><input class="form-control input-sm" type="number" name="sort_order" value="0" style="width:55px;"></td>
                                        <td class="text-center"><input type="checkbox" name="is_default" value="1"></td>
                                        <td class="text-center"><input type="checkbox" name="is_active" value="1" checked></td>
                                        <td><button class="btn btn-xs btn-success" type="submit">Add</button></td>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No authorized signatories yet.</p>
        @endforelse

        <hr>
        @if(config('authorized_signatories.' . $constructionContract->code))
            <form method="POST" action="{{ route('admin.authorized-signatories.import-suggested', $constructionContract->id) }}" class="form-inline" style="margin-bottom:15px;" onsubmit="return confirm('Import suggested signatories for {{ $constructionContract->code }}? Existing entries with the same name/job title will be skipped.');">
                @csrf
                <button class="btn btn-info" type="submit">Import suggested signatories ({{ $constructionContract->code }})</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.authorized-signatories.store', $constructionContract->id) }}" class="form-inline" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Add Signatory</label><br>
                <input class="form-control" type="text" name="name" placeholder="( Name Surname )" required style="width:280px;">
            </div>
            <div class="form-group">
                <label>Name (RFI)</label><br>
                <input class="form-control" type="text" name="name_rfi" placeholder="optional" style="width:220px;">
            </div>
            <div class="form-group">
                <label>Signature File</label><br>
                <select class="form-control" name="signature_path" style="width:180px; display:inline-block;">
                    <option value="">- none -</option>
                    @foreach($signature_files as $file)
                        <option value="{{ $file }}">{{ $file }}</option>
                    @endforeach
                </select>
                <span class="sig-upload-wrap" style="display:inline-block;">
                    <label class="btn btn-xs btn-default" style="margin-bottom:0;">
                        Upload&hellip;
                        <input type="file" name="signature_file" accept="image/*" style="display:none;">
                    </label>
                    <span class="text-muted small sig-upload-name"></span>
                </span>
            </div>
            <div class="form-group">
                <label>Size H/W</label><br>
                <input class="form-control" type="number" name="signature_size_h" placeholder="H" style="width:70px; display:inline-block;">
                <input class="form-control" type="number" name="signature_size_w" placeholder="W" style="width:70px; display:inline-block;">
            </div>
            <div class="form-group">
                <label>Sort</label><br>
                <input class="form-control" type="number" name="sort_order" value="0" style="width:80px;">
            </div>
            <div class="checkbox"><label><input type="checkbox" name="is_default" value="1"> Default</label></div>
            <div class="checkbox"><label><input type="checkbox" name="is_active" value="1" checked> Active</label></div>
            <button class="btn btn-success" type="submit">Add</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).on('change', 'input[name="signature_file"]', function(){
        $(this).closest('.sig-upload-wrap').find('.sig-upload-name').text(this.files.length ? this.files[0].name : '');
    });
</script>
