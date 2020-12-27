<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNonConformanceNoticeRequest;
use App\Http\Requests\StoreNonConformanceNoticeRequest;
use App\Http\Requests\UpdateNonConformanceNoticeRequest;
use App\NonConformanceNotice;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Auth;


class NonConformanceNoticeController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('non_conformance_notice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NonConformanceNotice::with(['csc_issuers', 'team'])->select(sprintf('%s.*', (new NonConformanceNotice)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'non_conformance_notice_show';
                $editGate      = 'non_conformance_notice_edit';
                $deleteGate    = 'non_conformance_notice_delete';
                $crudRoutePart = 'non-conformance-notices';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : "";
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : "";
            });
            $table->editColumn('ref_no', function ($row) {
                return $row->ref_no ? $row->ref_no : "";
            });

            $table->editColumn('attachment', function ($row) {
                if (!$row->attachment) {
                    return '';
                }

                $links = [];

                foreach ($row->attachment as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('attachment_description', function ($row) {
                return $row->attachment_description ? $row->attachment_description : "";
            });
            $table->editColumn('status_ncn', function ($row) {
                return $row->status_ncn ? NonConformanceNotice::STATUS_NCN_SELECT[$row->status_ncn] : '';
            });
            $table->addColumn('csc_issuers_name', function ($row) {
                return $row->csc_issuers ? $row->csc_issuers->name : '';
            });

            $table->editColumn('file_upload', function ($row) {
                if (!$row->file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('cc_srt', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->cc_srt ? 'checked' : null) . '>';
            });
            $table->editColumn('cc_pmc', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->cc_pmc ? 'checked' : null) . '>';
            });
            $table->editColumn('cc_cec', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->cc_cec ? 'checked' : null) . '>';
            });
            $table->editColumn('cc_csc', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->cc_csc ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'attachment', 'csc_issuers', 'file_upload', 'cc_srt', 'cc_pmc', 'cc_cec', 'cc_csc']);

            return $table->make(true);
        }

        $users = User::get();
        $teams = Team::get();

        return view('admin.nonConformanceNotices.index', compact('users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('non_conformance_notice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $csc_issuers = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.nonConformanceNotices.create', compact('csc_issuers'));
    }

    public function store(StoreNonConformanceNoticeRequest $request)
    {
        $nonConformanceNotice = NonConformanceNotice::create($request->all());

        foreach ($request->input('attachment', []) as $file) {
            $nonConformanceNotice->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
        }

        foreach ($request->input('file_upload', []) as $file) {
            $nonConformanceNotice->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $nonConformanceNotice->id]);
        }

        return redirect()->route('admin.non-conformance-notices.index');
    }

    public function edit(NonConformanceNotice $nonConformanceNotice)
    {
        abort_if(Gate::denies('non_conformance_notice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $csc_issuers = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $nonConformanceNotice->load('csc_issuers', 'team');

        return view('admin.nonConformanceNotices.edit', compact('csc_issuers', 'nonConformanceNotice'));
    }

    public function update(UpdateNonConformanceNoticeRequest $request, NonConformanceNotice $nonConformanceNotice)
    {
        $nonConformanceNotice->update($request->all());

        if (count($nonConformanceNotice->attachment) > 0) {
            foreach ($nonConformanceNotice->attachment as $media) {
                if (!in_array($media->file_name, $request->input('attachment', []))) {
                    $media->delete();
                }
            }
        }

        $media = $nonConformanceNotice->attachment->pluck('file_name')->toArray();

        foreach ($request->input('attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $nonConformanceNotice->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
            }
        }

        if (count($nonConformanceNotice->file_upload) > 0) {
            foreach ($nonConformanceNotice->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $nonConformanceNotice->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $nonConformanceNotice->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.non-conformance-notices.index');
    }

    public function show(NonConformanceNotice $nonConformanceNotice)
    {
        abort_if(Gate::denies('non_conformance_notice_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonConformanceNotice->load('csc_issuers', 'team');

        return view('admin.nonConformanceNotices.show', compact('nonConformanceNotice'));
    }

    public function destroy(NonConformanceNotice $nonConformanceNotice)
    {
        abort_if(Gate::denies('non_conformance_notice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nonConformanceNotice->delete();

        return back();
    }

    public function massDestroy(MassDestroyNonConformanceNoticeRequest $request)
    {
        NonConformanceNotice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('non_conformance_notice_create') && Gate::denies('non_conformance_notice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new NonConformanceNotice();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
