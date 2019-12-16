<?php

namespace App\Http\Controllers\Admin;

use App\Department;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyJobtitleRequest;
use App\Http\Requests\StoreJobtitleRequest;
use App\Http\Requests\UpdateJobtitleRequest;
use App\Jobtitle;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class JobtitleController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Jobtitle::with(['departments'])->select(sprintf('%s.*', (new Jobtitle)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'jobtitle_show';
                $editGate      = 'jobtitle_edit';
                $deleteGate    = 'jobtitle_delete';
                $crudRoutePart = 'jobtitles';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('department', function ($row) {
                $labels = [];

                foreach ($row->departments as $department) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $department->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'department']);

            return $table->make(true);
        }

        return view('admin.jobtitles.index');
    }

    public function create()
    {
        abort_if(Gate::denies('jobtitle_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departments = Department::all()->pluck('name', 'id');

        return view('admin.jobtitles.create', compact('departments'));
    }

    public function store(StoreJobtitleRequest $request)
    {
        $jobtitle = Jobtitle::create($request->all());
        $jobtitle->departments()->sync($request->input('departments', []));

        return redirect()->route('admin.jobtitles.index');
    }

    public function edit(Jobtitle $jobtitle)
    {
        abort_if(Gate::denies('jobtitle_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departments = Department::all()->pluck('name', 'id');

        $jobtitle->load('departments');

        return view('admin.jobtitles.edit', compact('departments', 'jobtitle'));
    }

    public function update(UpdateJobtitleRequest $request, Jobtitle $jobtitle)
    {
        $jobtitle->update($request->all());
        $jobtitle->departments()->sync($request->input('departments', []));

        return redirect()->route('admin.jobtitles.index');
    }

    public function show(Jobtitle $jobtitle)
    {
        abort_if(Gate::denies('jobtitle_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jobtitle->load('departments');

        return view('admin.jobtitles.show', compact('jobtitle'));
    }

    public function destroy(Jobtitle $jobtitle)
    {
        abort_if(Gate::denies('jobtitle_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jobtitle->delete();

        return back();
    }

    public function massDestroy(MassDestroyJobtitleRequest $request)
    {
        Jobtitle::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
