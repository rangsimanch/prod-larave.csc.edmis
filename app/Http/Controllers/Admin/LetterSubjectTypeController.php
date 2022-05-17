<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLetterSubjectTypeRequest;
use App\Http\Requests\StoreLetterSubjectTypeRequest;
use App\Http\Requests\UpdateLetterSubjectTypeRequest;
use App\LetterSubjectType;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LetterSubjectTypeController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('letter_subject_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LetterSubjectType::with(['team'])->select(sprintf('%s.*', (new LetterSubjectType())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'letter_subject_type_show';
                $editGate = 'letter_subject_type_edit';
                $deleteGate = 'letter_subject_type_delete';
                $crudRoutePart = 'letter-subject-types';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('subject_name', function ($row) {
                return $row->subject_name ? $row->subject_name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $teams = Team::get();

        return view('admin.letterSubjectTypes.index', compact('teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('letter_subject_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.letterSubjectTypes.create');
    }

    public function store(StoreLetterSubjectTypeRequest $request)
    {
        $letterSubjectType = LetterSubjectType::create($request->all());

        return redirect()->route('admin.letter-subject-types.index');
    }

    public function edit(LetterSubjectType $letterSubjectType)
    {
        abort_if(Gate::denies('letter_subject_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterSubjectType->load('team');

        return view('admin.letterSubjectTypes.edit', compact('letterSubjectType'));
    }

    public function update(UpdateLetterSubjectTypeRequest $request, LetterSubjectType $letterSubjectType)
    {
        $letterSubjectType->update($request->all());

        return redirect()->route('admin.letter-subject-types.index');
    }

    public function show(LetterSubjectType $letterSubjectType)
    {
        abort_if(Gate::denies('letter_subject_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterSubjectType->load('team');

        return view('admin.letterSubjectTypes.show', compact('letterSubjectType'));
    }

    public function destroy(LetterSubjectType $letterSubjectType)
    {
        abort_if(Gate::denies('letter_subject_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterSubjectType->delete();

        return back();
    }

    public function massDestroy(MassDestroyLetterSubjectTypeRequest $request)
    {
        LetterSubjectType::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
