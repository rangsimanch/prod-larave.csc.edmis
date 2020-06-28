<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRequestForInformationRequest;
use App\Http\Requests\StoreRequestForInformationRequest;
use App\Http\Requests\UpdateRequestForInformationRequest;
use App\RequestForInformation;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RequestForInformationController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('request_for_information_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RequestForInformation::with(['construction_contract', 'to', 'request_by', 'authorised_rep', 'response_organization', 'team'])->select(sprintf('%s.*', (new RequestForInformation)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'request_for_information_show';
                $editGate      = 'request_for_information_edit';
                $deleteGate    = 'request_for_information_delete';
                $crudRoutePart = 'request-for-informations';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('to_organization', function ($row) {
                return $row->to_organization ? $row->to_organization : "";
            });
            $table->editColumn('attention_name', function ($row) {
                return $row->attention_name ? $row->attention_name : "";
            });
            $table->editColumn('document_no', function ($row) {
                return $row->document_no ? $row->document_no : "";
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->addColumn('to_code', function ($row) {
                return $row->to ? $row->to->code : '';
            });

            $table->editColumn('discipline', function ($row) {
                return $row->discipline ? $row->discipline : "";
            });
            $table->editColumn('originator_name', function ($row) {
                return $row->originator_name ? $row->originator_name : "";
            });
            $table->editColumn('cc_to', function ($row) {
                return $row->cc_to ? $row->cc_to : "";
            });
            $table->editColumn('incoming_no', function ($row) {
                return $row->incoming_no ? $row->incoming_no : "";
            });

            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : "";
            });
            $table->editColumn('attachment_file_description', function ($row) {
                return $row->attachment_file_description ? $row->attachment_file_description : "";
            });
            $table->editColumn('attachment_files', function ($row) {
                if (!$row->attachment_files) {
                    return '';
                }

                $links = [];

                foreach ($row->attachment_files as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->addColumn('request_by_name', function ($row) {
                return $row->request_by ? $row->request_by->name : '';
            });

            $table->editColumn('outgoing_no', function ($row) {
                return $row->outgoing_no ? $row->outgoing_no : "";
            });

            $table->editColumn('response', function ($row) {
                return $row->response ? $row->response : "";
            });
            $table->addColumn('authorised_rep_name', function ($row) {
                return $row->authorised_rep ? $row->authorised_rep->name : '';
            });

            $table->addColumn('response_organization_code', function ($row) {
                return $row->response_organization ? $row->response_organization->code : '';
            });

            $table->editColumn('response_date', function ($row) {
                return $row->response_date ? $row->response_date : "";
            });
            $table->editColumn('record', function ($row) {
                return $row->record ? $row->record : "";
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'to', 'attachment_files', 'request_by', 'authorised_rep', 'response_organization']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('code')->toArray();
        $users                  = User::get()->pluck('name')->toArray();
        $users                  = User::get()->pluck('name')->toArray();
        $teams                  = Team::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('name')->toArray();

        return view('admin.requestForInformations.index', compact('construction_contracts', 'teams', 'users', 'users', 'teams', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('request_for_information_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tos = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $request_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $authorised_reps = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $response_organizations = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.requestForInformations.create', compact('construction_contracts', 'tos', 'request_bies', 'authorised_reps', 'response_organizations'));
    }

    public function store(StoreRequestForInformationRequest $request)
    {
        $requestForInformation = RequestForInformation::create($request->all());

        foreach ($request->input('attachment_files', []) as $file) {
            $requestForInformation->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment_files');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $requestForInformation->id]);
        }

        return redirect()->route('admin.request-for-informations.index');
    }

    public function edit(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tos = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $request_bies = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $authorised_reps = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $response_organizations = Team::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $requestForInformation->load('construction_contract', 'to', 'request_by', 'authorised_rep', 'response_organization', 'team');

        return view('admin.requestForInformations.edit', compact('construction_contracts', 'tos', 'request_bies', 'authorised_reps', 'response_organizations', 'requestForInformation'));
    }

    public function update(UpdateRequestForInformationRequest $request, RequestForInformation $requestForInformation)
    {
        $requestForInformation->update($request->all());

        if (count($requestForInformation->attachment_files) > 0) {
            foreach ($requestForInformation->attachment_files as $media) {
                if (!in_array($media->file_name, $request->input('attachment_files', []))) {
                    $media->delete();
                }
            }
        }

        $media = $requestForInformation->attachment_files->pluck('file_name')->toArray();

        foreach ($request->input('attachment_files', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $requestForInformation->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachment_files');
            }
        }

        return redirect()->route('admin.request-for-informations.index');
    }

    public function show(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInformation->load('construction_contract', 'to', 'request_by', 'authorised_rep', 'response_organization', 'team');

        return view('admin.requestForInformations.show', compact('requestForInformation'));
    }

    public function destroy(RequestForInformation $requestForInformation)
    {
        abort_if(Gate::denies('request_for_information_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $requestForInformation->delete();

        return back();
    }

    public function massDestroy(MassDestroyRequestForInformationRequest $request)
    {
        RequestForInformation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('request_for_information_create') && Gate::denies('request_for_information_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new RequestForInformation();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
