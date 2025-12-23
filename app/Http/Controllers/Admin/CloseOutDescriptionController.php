<?php

namespace App\Http\Controllers\Admin;

use App\CloseOutDescription;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCloseOutDescriptionRequest;
use App\Http\Requests\StoreCloseOutDescriptionRequest;
use App\Http\Requests\UpdateCloseOutDescriptionRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CloseOutDescriptionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('close_out_description_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CloseOutDescription::query()->select(sprintf('%s.*', (new CloseOutDescription)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'close_out_description_show';
                $editGate      = 'close_out_description_edit';
                $deleteGate    = 'close_out_description_delete';
                $crudRoutePart = 'close-out-descriptions';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.closeOutDescriptions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('close_out_description_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.closeOutDescriptions.create');
    }

    public function store(StoreCloseOutDescriptionRequest $request)
    {
        $closeOutDescription = CloseOutDescription::create($request->all());

        return redirect()->route('admin.close-out-descriptions.index');
    }

    public function edit(CloseOutDescription $closeOutDescription)
    {
        abort_if(Gate::denies('close_out_description_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.closeOutDescriptions.edit', compact('closeOutDescription'));
    }

    public function update(UpdateCloseOutDescriptionRequest $request, CloseOutDescription $closeOutDescription)
    {
        $closeOutDescription->update($request->all());

        return redirect()->route('admin.close-out-descriptions.index');
    }

    public function show(CloseOutDescription $closeOutDescription)
    {
        abort_if(Gate::denies('close_out_description_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.closeOutDescriptions.show', compact('closeOutDescription'));
    }

    public function destroy(CloseOutDescription $closeOutDescription)
    {
        abort_if(Gate::denies('close_out_description_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $closeOutDescription->delete();

        return back();
    }

    public function massDestroy(MassDestroyCloseOutDescriptionRequest $request)
    {
        $closeOutDescriptions = CloseOutDescription::find(request('ids'));

        foreach ($closeOutDescriptions as $closeOutDescription) {
            $closeOutDescription->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
