<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\DocumentsAndAssignment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocumentsAndAssignmentRequest;
use App\Http\Requests\StoreDocumentsAndAssignmentRequest;
use App\Http\Requests\UpdateDocumentsAndAssignmentRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocumentsAndAssignmentsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('documents_and_assignment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocumentsAndAssignment::with(['construction_contract'])->select(sprintf('%s.*', (new DocumentsAndAssignment)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'documents_and_assignment_show';
                $editGate      = 'documents_and_assignment_edit';
                $deleteGate    = 'documents_and_assignment_delete';
                $crudRoutePart = 'documents-and-assignments';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('file_name', function ($row) {
                return $row->file_name ? $row->file_name : "";
            });
            $table->editColumn('original_no', function ($row) {
                return $row->original_no ? $row->original_no : "";
            });
            $table->editColumn('receipt_no', function ($row) {
                return $row->receipt_no ? $row->receipt_no : "";
            });

            $table->editColumn('received_from', function ($row) {
                return $row->received_from ? $row->received_from : "";
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
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

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'file_upload']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();

        return view('admin.documentsAndAssignments.index', compact('construction_contracts'));
    }

    public function create()
    {
        abort_if(Gate::denies('documents_and_assignment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.documentsAndAssignments.create', compact('construction_contracts'));
    }

    public function store(StoreDocumentsAndAssignmentRequest $request)
    {
        $documentsAndAssignment = DocumentsAndAssignment::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $documentsAndAssignment->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $documentsAndAssignment->id]);
        }

        return redirect()->route('admin.documents-and-assignments.index');
    }

    public function edit(DocumentsAndAssignment $documentsAndAssignment)
    {
        abort_if(Gate::denies('documents_and_assignment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $documentsAndAssignment->load('construction_contract');

        return view('admin.documentsAndAssignments.edit', compact('construction_contracts', 'documentsAndAssignment'));
    }

    public function update(UpdateDocumentsAndAssignmentRequest $request, DocumentsAndAssignment $documentsAndAssignment)
    {
        $documentsAndAssignment->update($request->all());

        if (count($documentsAndAssignment->file_upload) > 0) {
            foreach ($documentsAndAssignment->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $documentsAndAssignment->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $documentsAndAssignment->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.documents-and-assignments.index');
    }

    public function show(DocumentsAndAssignment $documentsAndAssignment)
    {
        abort_if(Gate::denies('documents_and_assignment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentsAndAssignment->load('construction_contract');

        return view('admin.documentsAndAssignments.show', compact('documentsAndAssignment'));
    }

    public function destroy(DocumentsAndAssignment $documentsAndAssignment)
    {
        abort_if(Gate::denies('documents_and_assignment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentsAndAssignment->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocumentsAndAssignmentRequest $request)
    {
        DocumentsAndAssignment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('documents_and_assignment_create') && Gate::denies('documents_and_assignment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocumentsAndAssignment();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
