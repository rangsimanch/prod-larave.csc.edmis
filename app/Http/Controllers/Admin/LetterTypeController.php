<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLetterTypeRequest;
use App\Http\Requests\StoreLetterTypeRequest;
use App\Http\Requests\UpdateLetterTypeRequest;
use App\LetterType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LetterTypeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('letter_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LetterType::query()->select(sprintf('%s.*', (new LetterType)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'letter_type_show';
                $editGate      = 'letter_type_edit';
                $deleteGate    = 'letter_type_delete';
                $crudRoutePart = 'letter-types';

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
            $table->editColumn('type_title', function ($row) {
                return $row->type_title ? $row->type_title : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.letterTypes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('letter_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.letterTypes.create');
    }

    public function store(StoreLetterTypeRequest $request)
    {
        $letterType = LetterType::create($request->all());

        return redirect()->route('admin.letter-types.index');
    }

    public function edit(LetterType $letterType)
    {
        abort_if(Gate::denies('letter_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.letterTypes.edit', compact('letterType'));
    }

    public function update(UpdateLetterTypeRequest $request, LetterType $letterType)
    {
        $letterType->update($request->all());

        return redirect()->route('admin.letter-types.index');
    }

    public function show(LetterType $letterType)
    {
        abort_if(Gate::denies('letter_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.letterTypes.show', compact('letterType'));
    }

    public function destroy(LetterType $letterType)
    {
        abort_if(Gate::denies('letter_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterType->delete();

        return back();
    }

    public function massDestroy(MassDestroyLetterTypeRequest $request)
    {
        LetterType::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
