<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySiteWarningNoticeRequest;
use App\Http\Requests\StoreSiteWarningNoticeRequest;
use App\Http\Requests\UpdateSiteWarningNoticeRequest;
use App\SiteWarningNotice;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Auth;


class SiteWarningNoticeController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('site_warning_notice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SiteWarningNotice::with(['to_team', 'construction_contract', 'issue_by', 'reviewed_by', 'containment_responsible', 'corrective_responsible', 'section_2_reviewed_by', 'section_2_approved_by', 'csc_issuer', 'csc_qa', 'csc_pm', 'team'])->select(sprintf('%s.*', (new SiteWarningNotice)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'site_warning_notice_show';
                $editGate      = 'site_warning_notice_edit';
                $deleteGate    = 'site_warning_notice_delete';
                $crudRoutePart = 'site-warning-notices';

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
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : "";
            });
            $table->editColumn('reply_by_ncr', function ($row) {
                return $row->reply_by_ncr ? SiteWarningNotice::REPLY_BY_NCR_SELECT[$row->reply_by_ncr] : '';
            });
            $table->editColumn('swn_no', function ($row) {
                return $row->swn_no ? $row->swn_no : "";
            });

            $table->addColumn('to_team_code', function ($row) {
                return $row->to_team ? $row->to_team->code : '';
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : "";
            });
            $table->addColumn('issue_by_name', function ($row) {
                return $row->issue_by ? $row->issue_by->name : '';
            });

            $table->addColumn('reviewed_by_name', function ($row) {
                return $row->reviewed_by ? $row->reviewed_by->name : '';
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
            $table->editColumn('root_cause', function ($row) {
                return $row->root_cause ? $row->root_cause : "";
            });
            $table->addColumn('containment_responsible_name', function ($row) {
                return $row->containment_responsible ? $row->containment_responsible->name : '';
            });

            $table->addColumn('corrective_responsible_name', function ($row) {
                return $row->corrective_responsible ? $row->corrective_responsible->name : '';
            });

            $table->addColumn('section_2_reviewed_by_name', function ($row) {
                return $row->section_2_reviewed_by ? $row->section_2_reviewed_by->name : '';
            });

            $table->addColumn('section_2_approved_by_name', function ($row) {
                return $row->section_2_approved_by ? $row->section_2_approved_by->name : '';
            });

            $table->editColumn('review_and_judgement_status', function ($row) {
                return $row->review_and_judgement_status ? SiteWarningNotice::REVIEW_AND_JUDGEMENT_STATUS_SELECT[$row->review_and_judgement_status] : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : "";
            });
            $table->addColumn('csc_issuer_name', function ($row) {
                return $row->csc_issuer ? $row->csc_issuer->name : '';
            });

            $table->addColumn('csc_qa_name', function ($row) {
                return $row->csc_qa ? $row->csc_qa->name : '';
            });

            $table->editColumn('disposition_status', function ($row) {
                return $row->disposition_status ? SiteWarningNotice::DISPOSITION_STATUS_SELECT[$row->disposition_status] : '';
            });
            $table->addColumn('csc_pm_name', function ($row) {
                return $row->csc_pm ? $row->csc_pm->name : '';
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

            $table->rawColumns(['actions', 'placeholder', 'to_team', 'construction_contract', 'issue_by', 'reviewed_by', 'attachment', 'containment_responsible', 'corrective_responsible', 'section_2_reviewed_by', 'section_2_approved_by', 'csc_issuer', 'csc_qa', 'csc_pm', 'file_upload']);

            return $table->make(true);
        }

        $teams                  = Team::get();
        $construction_contracts = ConstructionContract::get();
        $users                  = User::get();
        $users                  = User::get();
        $users                  = User::get();
        $users                  = User::get();
        $users                  = User::get();
        $users                  = User::get();
        $users                  = User::get();
        $users                  = User::get();
        $users                  = User::get();
        $teams                  = Team::get();

        return view('admin.siteWarningNotices.index', compact('teams', 'construction_contracts', 'users', 'users', 'users', 'users', 'users', 'users', 'users', 'users', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('site_warning_notice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $to_teams = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        //Contract Check
            //Check is Admin
            if(Auth::id() != 1){
                $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            else{
                $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }

        $issue_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reviewed_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $containment_responsibles = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $corrective_responsibles = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $section_2_reviewed_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $section_2_approved_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $csc_issuers = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $csc_qas = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $csc_pms = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.siteWarningNotices.create', compact('to_teams', 'construction_contracts', 'issue_bies', 'reviewed_bies', 'containment_responsibles', 'corrective_responsibles', 'section_2_reviewed_bies', 'section_2_approved_bies', 'csc_issuers', 'csc_qas', 'csc_pms'));
    }

    public function store(StoreSiteWarningNoticeRequest $request)
    {
        $data = $request->all();
        $data['to_team_id'] = 4;   
        $siteWarningNotice = SiteWarningNotice::create($data);

        foreach ($request->input('attachment', []) as $file) {
            $siteWarningNotice->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
        }

        foreach ($request->input('file_upload', []) as $file) {
            $siteWarningNotice->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $siteWarningNotice->id]);
        }

        return redirect()->route('admin.site-warning-notices.index');
    }

    public function edit(SiteWarningNotice $siteWarningNotice)
    {
        abort_if(Gate::denies('site_warning_notice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $to_teams = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        //Contract Check
            //Check is Admin
            if(Auth::id() != 1){
                $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            else{
                $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
            
        $issue_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reviewed_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $containment_responsibles = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $corrective_responsibles = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $section_2_reviewed_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $section_2_approved_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $csc_issuers = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $csc_qas = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $csc_pms = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $siteWarningNotice->load('to_team', 'construction_contract', 'issue_by', 'reviewed_by', 'containment_responsible', 'corrective_responsible', 'section_2_reviewed_by', 'section_2_approved_by', 'csc_issuer', 'csc_qa', 'csc_pm', 'team');

        return view('admin.siteWarningNotices.edit', compact('to_teams', 'construction_contracts', 'issue_bies', 'reviewed_bies', 'containment_responsibles', 'corrective_responsibles', 'section_2_reviewed_bies', 'section_2_approved_bies', 'csc_issuers', 'csc_qas', 'csc_pms', 'siteWarningNotice'));
    }

    public function update(UpdateSiteWarningNoticeRequest $request, SiteWarningNotice $siteWarningNotice)
    {
        $siteWarningNotice->update($request->all());

        if (count($siteWarningNotice->attachment) > 0) {
            foreach ($siteWarningNotice->attachment as $media) {
                if (!in_array($media->file_name, $request->input('attachment', []))) {
                    $media->delete();
                }
            }
        }

        $media = $siteWarningNotice->attachment->pluck('file_name')->toArray();

        foreach ($request->input('attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $siteWarningNotice->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment');
            }
        }

        if (count($siteWarningNotice->file_upload) > 0) {
            foreach ($siteWarningNotice->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $siteWarningNotice->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $siteWarningNotice->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.site-warning-notices.index');
    }

    public function show(SiteWarningNotice $siteWarningNotice)
    {
        abort_if(Gate::denies('site_warning_notice_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $siteWarningNotice->load('to_team', 'construction_contract', 'issue_by', 'reviewed_by', 'containment_responsible', 'corrective_responsible', 'section_2_reviewed_by', 'section_2_approved_by', 'csc_issuer', 'csc_qa', 'csc_pm', 'team');

        return view('admin.siteWarningNotices.show', compact('siteWarningNotice'));
    }

    public function destroy(SiteWarningNotice $siteWarningNotice)
    {
        abort_if(Gate::denies('site_warning_notice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $siteWarningNotice->delete();

        return back();
    }

    public function massDestroy(MassDestroySiteWarningNoticeRequest $request)
    {
        SiteWarningNotice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('site_warning_notice_create') && Gate::denies('site_warning_notice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SiteWarningNotice();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
