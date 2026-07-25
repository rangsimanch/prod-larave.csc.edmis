<?php

namespace App\Http\Controllers\Admin;

use App\BoQ;
use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Rfa;
use App\RfaCommentStatus;
use App\RfaDocumentStatus;
use App\Rfatype;
use App\Team;
use App\User;
use App\WbsLevelOne;
use App\WbsLevelThree;
use App\Wbslevelfour;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DrawingRfaController extends Controller
{
    /**
     * Pre-filtered RFA list pages for the Design team.
     * Drawing = type_id 1, Shop Drawing = type_id 9, As-Built Drawing = type_id 10.
     * Read-only: no create/edit/delete, no CSV import, no bulk download.
     */

    public function drawingIndex(Request $request)
    {
        return $this->buildIndex($request, 1, 'Drawing', route('admin.drawings.index'));
    }

    public function shopDrawingIndex(Request $request)
    {
        return $this->buildIndex($request, 9, 'Shop Drawing', route('admin.shop-drawing-rfas.index'));
    }

    public function asBuiltIndex(Request $request)
    {
        return $this->buildIndex($request, 10, 'As-Built Drawing', route('admin.as-built-drawings.index'));
    }

    public function methodStatementIndex(Request $request)
    {
        return $this->buildIndex($request, ['MAT', 'SUB'], 'Method Statement', route('admin.method-statements.index'));
    }

    protected function buildIndex(Request $request, $typeFilter, $pageTitle, $ajaxRoute)
    {
        abort_if(Gate::denies('rfa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Rfa::with(['document_status', 'boq', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'issueby', 'assign', 'action_by', 'comment_by', 'information_by', 'comment_status', 'for_status', 'create_by_user', 'distribute_by', 'reviewed_by', 'wbs_level_one', 'team'])
                ->orderBy('created_at', 'desc')
                ->select(sprintf('%s.*', (new Rfa())->table));

            if (is_array($typeFilter)) {
                $query->whereHas('type', function ($q) use ($typeFilter) {
                    $q->whereIn('type_code', $typeFilter);
                });
            } else {
                $query->where('type_id', $typeFilter);
            }
            $table = Datatables::of($query);

            $table->editColumn('cover_sheet', function ($row) {
                $cover_sheet = [];
                $cover_sheet[] = '<a style="font-size:12px" class="btn btn-default" href="' . route('admin.rfas.createReportRFA', $row->id) . '" target="_blank">
                            RFA<br>Report </a>';
                return implode(' ', $cover_sheet);
            });

            $table->addColumn('document_status_status_name', function ($row) {
                if ($row->document_status->status_name == 'New')
                    return sprintf('<p style="color:#003399"><b>%s</b></p>', $row->document_status ? $row->document_status->status_name : '');
                else if ($row->document_status->status_name == 'Distributed')
                    return sprintf('<p style="color:#ff9900"><b>%s</b></p>', $row->document_status ? $row->document_status->status_name : '');
                else if ($row->document_status->status_name == 'Reviewed')
                    return sprintf('<p style="color:#6600cc"><b>%s</b></p>', $row->document_status ? $row->document_status->status_name : '');
                else if ($row->document_status->status_name == 'Done')
                    return sprintf('<p style="color:#009933"><b>%s</b></p>', $row->document_status ? $row->document_status->status_name : '');
                else
                    return $row->document_status ? $row->document_status->status_name : '';
            });

            $table->editColumn('file_upload_1', function ($row) {
                if (!$row->file_upload_1) {
                    return '';
                }
                $links = [];
                foreach ($row->file_upload_1 as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }
                return implode(', ', $links);
            });

            $table->addColumn('boq_name', function ($row) {
                return $row->boq ? $row->boq->name : '';
            });

            $table->editColumn('title_eng', function ($row) {
                return $row->title_eng ? $row->title_eng : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('origin_number', function ($row) {
                return $row->origin_number ? $row->origin_number : '';
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });

            $table->editColumn('type.type_code', function ($row) {
                return $row->type ? (is_string($row->type) ? $row->type : $row->type->type_code) : '';
            });
            $table->editColumn('worktype', function ($row) {
                return $row->worktype ? Rfa::WORKTYPE_SELECT[$row->worktype] : '';
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });
            $table->addColumn('wbs_level_3_wbs_level_3_code', function ($row) {
                return $row->wbs_level_3 ? $row->wbs_level_3->wbs_level_3_code : '';
            });
            $table->addColumn('wbs_level_4_wbs_level_4_code', function ($row) {
                return $row->wbs_level_4 ? $row->wbs_level_4->wbs_level_4_code : '';
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });
            $table->editColumn('submit_date', function ($row) {
                return $row->submit_date ? $row->submit_date : '';
            });

            $table->addColumn('comment_status_name', function ($row) {
                return $row->comment_status ? e($row->comment_status->name) : '';
            });

            $table->editColumn('commercial_file_upload', function ($row) {
                if (!$row->commercial_file_upload) {
                    return '';
                }
                $links = [];
                foreach ($row->commercial_file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }
                return implode(', ', $links);
            });

            $table->rawColumns(['cover_sheet', 'document_status_status_name', 'file_upload_1', 'commercial_file_upload', 'type', 'construction_contract', 'wbs_level_3', 'wbs_level_4', 'comment_status']);

            return $table->make(true);
        }

        $rfa_document_statuses  = RfaDocumentStatus::get();
        $bo_qs                  = BoQ::orderBy('code', 'asc')->get();
        $rfatypes               = Rfatype::get();
        $construction_contracts = ConstructionContract::where('id', '!=', 15)->get();
        $wbs_level_threes       = WbsLevelThree::orderBy('wbs_level_3_code', 'asc')->get();
        $wbslevelfours          = Wbslevelfour::orderBy('wbs_level_4_code', 'asc')->get();
        $rfa_comment_statuses   = RfaCommentStatus::get();

        return view('admin.drawingRfas.index', compact(
            'rfa_document_statuses',
            'bo_qs',
            'rfatypes',
            'construction_contracts',
            'wbs_level_threes',
            'wbslevelfours',
            'rfa_comment_statuses',
            'pageTitle',
            'ajaxRoute'
        ));
    }
}
