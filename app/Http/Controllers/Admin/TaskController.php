<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Task;
use App\TaskStatus;
use App\TaskTag;
use App\User;
use App\ConstructionContract;
use Illuminate\Support\Facades\Auth;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class TaskController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Task::with(['status', 'tags', 'user_create', 'construction_contracts', 'team'])->select(sprintf('%s.*', (new Task)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'task_show';
                $editGate      = 'task_edit';
                $deleteGate    = 'task_delete';
                $crudRoutePart = 'tasks';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : "";
            });
            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });

            $table->editColumn('tag', function ($row) {
                $labels = [];

                foreach ($row->tags as $tag) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $tag->name);
                }

                return implode(' ', $labels);
            });

            $table->editColumn('attachment', function ($row) {
                return $row->attachment ? '<a href="' . $row->attachment->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->addColumn('user_create_name', function ($row) {
                return $row->user_create ? $row->user_create->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'tag', 'attachment', 'user_create']);

            return $table->make(true);
        }

        return view('admin.tasks.index');
    }

     /**
     * Show the form for creating new Product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = TaskStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tags = TaskTag::all()->pluck('name', 'id');

        // $construction_contract_id = ConstructionContract::has('constructionContractTasks')->get()->pluck('code', 'id');
        // $user_create_id = ConstructionContract::with('constructionContractTasks')->findOrFail(session('construction_contract_id'))
        //      ->constructionContractTasks->pluck('code', 'id');

        // //$user_creates = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tasks.create', compact('statuses', 'tags'));
    }

    public function store(StoreTaskRequest $request)
    {

        $data = $request->all();
        $data['construction_contract_id'] = $data['construction_contract_id'] ?? session('construction_contract_id');
        $data['user_create_id'] = $data['user_create_id'] ?? Auth::id();
        $task = Task::create($data);

        // $task = Task::create($request->all());
        // $task->tags()->sync($request->input('tags', []));
        // $task->construction_contracts()->sync($request->input('construction_contracts', []));

        if ($request->input('attachment', false)) {
            $task->addMedia(storage_path('tmp/uploads/' . $request->input('attachment')))->toMediaCollection('attachment');
        }

        return redirect()->route('admin.tasks.index');
    }

    public function edit(Task $task)
    {
        abort_if(Gate::denies('task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = TaskStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tags = TaskTag::all()->pluck('name', 'id');

        $user_creates = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        $task->load('status', 'tags', 'user_create', 'construction_contracts', 'team');

        return view('admin.tasks.edit', compact('statuses', 'tags', 'user_creates', 'task'));
    }

    /**
     * Update Product in storage.
     *
     * @param  \App\Http\Requests\UpdateProductsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        // $task->update($request->all());
        // $task->tags()->sync($request->input('tags', []));
        // $task->construction_contracts()->sync($request->input('construction_contracts', []));

        $data = $request->all();
        $data['construction_contract_id'] = $data['construction_contract_id'] ?? session('construction_contract_id');
        $data['user_create_id'] = $data['user_create_id'] ?? Auth::id();
        $task = Task::findOrFail($id);
        $task->update($data);


        if ($request->input('attachment', false)) {
            if (!$task->attachment || $request->input('attachment') !== $task->attachment->file_name) {
                $task->addMedia(storage_path('tmp/uploads/' . $request->input('attachment')))->toMediaCollection('attachment');
            }
        } elseif ($task->attachment) {
            $task->attachment->delete();
        }

        return redirect()->route('admin.tasks.index');
    }


    public function show(Task $task)
    {
        abort_if(Gate::denies('task_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->load('status', 'tags', 'user_create', 'construction_contracts', 'team');
        //$task = Task::findOrFail($id);

        return view('admin.tasks.show', compact('task'));
    }

    public function destroy(Task $task)
    {
        abort_if(Gate::denies('task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->delete();

        return back();
    }

    public function massDestroy(MassDestroyTaskRequest $request)
    {
        Task::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
