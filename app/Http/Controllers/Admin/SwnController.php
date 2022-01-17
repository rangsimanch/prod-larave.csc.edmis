<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySwnRequest;
use App\Http\Requests\StoreSwnRequest;
use App\Http\Requests\UpdateSwnRequest;
use App\Swn;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SwnController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('swn_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Swn::with(['construction_contract', 'issue_by', 'responsible', 'related_specialist', 'leader', 'construction_specialist', 'team'])->select(sprintf('%s.*', (new Swn())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'swn_show';
                $editGate = 'swn_edit';
                $deleteGate = 'swn_delete';
                $crudRoutePart = 'swns';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });

            $table->editColumn('document_attachment', function ($row) {
                if (!$row->document_attachment) {
                    return '';
                }
                $links = [];
                foreach ($row->document_attachment as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('issue_by_name', function ($row) {
                return $row->issue_by ? $row->issue_by->name : '';
            });

            $table->addColumn('responsible_name', function ($row) {
                return $row->responsible ? $row->responsible->name : '';
            });

            $table->editColumn('review_status', function ($row) {
                return $row->review_status ? Swn::REVIEW_STATUS_SELECT[$row->review_status] : '';
            });
            $table->editColumn('documents_status', function ($row) {
                return $row->documents_status ? Swn::DOCUMENTS_STATUS_SELECT[$row->documents_status] : '';
            });
            $table->addColumn('related_specialist_name', function ($row) {
                return $row->related_specialist ? $row->related_specialist->name : '';
            });

            $table->addColumn('leader_name', function ($row) {
                return $row->leader ? $row->leader->name : '';
            });

            $table->addColumn('construction_specialist_name', function ($row) {
                return $row->construction_specialist ? $row->construction_specialist->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'document_attachment', 'issue_by', 'responsible', 'related_specialist', 'leader', 'construction_specialist']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get();
        $users                  = User::get();
        $teams                  = Team::get();

        return view('admin.swns.index', compact('construction_contracts', 'users', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('swn_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issue_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $responsibles = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $related_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaders = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.swns.create', compact('construction_contracts', 'construction_specialists', 'issue_bies', 'leaders', 'related_specialists', 'responsibles'));
    }

    public function store(StoreSwnRequest $request)
    {
        $swn = Swn::create($request->all());

        foreach ($request->input('document_attachment', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('document_attachment');
        }

        foreach ($request->input('description_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
        }

        foreach ($request->input('rootcase_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
        }

        foreach ($request->input('containment_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
        }

        foreach ($request->input('corrective_image', []) as $file) {
            $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $swn->id]);
        }

        return redirect()->route('admin.swns.index');
    }

    public function edit(Swn $swn)
    {
        abort_if(Gate::denies('swn_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $issue_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $responsibles = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $related_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $leaders = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_specialists = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $swn->load('construction_contract', 'issue_by', 'responsible', 'related_specialist', 'leader', 'construction_specialist', 'team');

        return view('admin.swns.edit', compact('construction_contracts', 'construction_specialists', 'issue_bies', 'leaders', 'related_specialists', 'responsibles', 'swn'));
    }

    public function update(UpdateSwnRequest $request, Swn $swn)
    {
        $swn->update($request->all());

        if (count($swn->document_attachment) > 0) {
            foreach ($swn->document_attachment as $media) {
                if (!in_array($media->file_name, $request->input('document_attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->document_attachment->pluck('file_name')->toArray();
        foreach ($request->input('document_attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('document_attachment');
            }
        }

        if (count($swn->description_image) > 0) {
            foreach ($swn->description_image as $media) {
                if (!in_array($media->file_name, $request->input('description_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->description_image->pluck('file_name')->toArray();
        foreach ($request->input('description_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('description_image');
            }
        }

        if (count($swn->rootcase_image) > 0) {
            foreach ($swn->rootcase_image as $media) {
                if (!in_array($media->file_name, $request->input('rootcase_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->rootcase_image->pluck('file_name')->toArray();
        foreach ($request->input('rootcase_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('rootcase_image');
            }
        }

        if (count($swn->containment_image) > 0) {
            foreach ($swn->containment_image as $media) {
                if (!in_array($media->file_name, $request->input('containment_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->containment_image->pluck('file_name')->toArray();
        foreach ($request->input('containment_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('containment_image');
            }
        }

        if (count($swn->corrective_image) > 0) {
            foreach ($swn->corrective_image as $media) {
                if (!in_array($media->file_name, $request->input('corrective_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $swn->corrective_image->pluck('file_name')->toArray();
        foreach ($request->input('corrective_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $swn->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('corrective_image');
            }
        }

        return redirect()->route('admin.swns.index');
    }

    public function show(Swn $swn)
    {
        abort_if(Gate::denies('swn_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $swn->load('construction_contract', 'issue_by', 'responsible', 'related_specialist', 'leader', 'construction_specialist', 'team');

        return view('admin.swns.show', compact('swn'));
    }

    public function destroy(Swn $swn)
    {
        abort_if(Gate::denies('swn_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $swn->delete();

        return back();
    }

    public function massDestroy(MassDestroySwnRequest $request)
    {
        Swn::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('swn_create') && Gate::denies('swn_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Swn();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
