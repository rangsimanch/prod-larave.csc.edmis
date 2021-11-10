<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Jobtitle;
use App\Organization;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\Team;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;


class UsersController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if(auth()->user()->roles->contains(1)){
                $query = User::with(['organization', 'team', 'jobtitle', 'roles', 'construction_contracts'])
                ->select(sprintf('%s.*', (new User)->table));
            }
            else{
                $query = User::with(['organization', 'team', 'jobtitle', 'roles', 'construction_contracts'])
                ->select(sprintf('%s.*', (new User)->table))
                ->whereHas('construction_contracts', function($q) {
                    $q->where('construction_contract_id', session('construction_contract_id'));
                });
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'user_show';
                $editGate      = 'user_edit';
                $deleteGate    = 'user_delete';
                $crudRoutePart = 'users';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('img_user', function ($row) {
                if ($photo = $row->img_user) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });

            $table->addColumn('organization_title_th', function ($row) {
                return $row->organization ? $row->organization->title_th : '';
            });

            $table->editColumn('gender', function ($row) {
                return $row->gender ? User::GENDER_SELECT[$row->gender] : '';
            });
            $table->editColumn('workphone', function ($row) {
                return $row->workphone ? $row->workphone : "";
            });
            $table->addColumn('team_name', function ($row) {
                return $row->team ? $row->team->name : '';
            });

            $table->addColumn('jobtitle_name', function ($row) {
                return $row->jobtitle ? $row->jobtitle->name : '';
            });

            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : "";
            });

            $table->editColumn('roles', function ($row) {
                $labels = [];

                foreach ($row->roles as $role) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $role->title);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('signature', function ($row) {
                if ($photo = $row->signature) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('approved', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->approved ? 'checked' : null) . '>';
            });
            $table->editColumn('construction_contract', function ($row) {
                $labels = [];

                foreach ($row->construction_contracts as $construction_contract) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $construction_contract->code);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'organization', 'img_user', 'team', 'jobtitle', 'roles', 'signature', 'approved', 'construction_contract']);

            return $table->make(true);
        }

        $organizations          = Organization::get();
        $teams                  = Team::get();
        $jobtitles              = Jobtitle::get();
        $roles                  = Role::get();
        $construction_contracts = ConstructionContract::get();

        session(['previous-url' => route('admin.users.index')]);
        return view('admin.users.index', compact('organizations', 'teams', 'jobtitles', 'roles', 'construction_contracts'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $organizations = Organization::all()->pluck('title_th', 'id')->prepend(trans('global.pleaseSelect'), '');

        $teams = Team::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jobtitles = Jobtitle::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if(Auth::id() != 1){
            $roles = Role::where('id','!=',1)->pluck('title', 'id');
        }
        else{
            $roles = Role::all()->pluck('title', 'id');
        }

        $construction_contracts = ConstructionContract::where('id', '!=', 15)->pluck('code', 'id');

        return view('admin.users.create', compact('organizations', 'teams', 'jobtitles', 'roles', 'construction_contracts'));
    }

    public function store(StoreUserRequest $request)
    {   
        $data = $request->all();
        $user = User::create($data);

        $user->roles()->sync($request->input('roles', []));
        $user->construction_contracts()->sync($request->input('construction_contracts', []));

        if ($request->input('img_user', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . $request->input('img_user')))->toMediaCollection('img_user');
        }

        if ($request->input('signature', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . $request->input('signature')))->toMediaCollection('signature');
        }

        if ($request->input('stamp_signature', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . $request->input('stamp_signature')))->toMediaCollection('stamp_signature');
        }

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $organizations = Organization::all()->pluck('title_th', 'id')->prepend(trans('global.pleaseSelect'), '');

        $teams = Team::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jobtitles = Jobtitle::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
    
        $roles = Role::all()->pluck('title', 'id');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id');

        $user->load('organization' ,'team', 'jobtitle', 'roles', 'construction_contracts');

        return view('admin.users.edit', compact('organizations','teams', 'jobtitles', 'roles', 'construction_contracts', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        $user->construction_contracts()->sync($request->input('construction_contracts', []));

        if ($request->input('img_user', false)) {
            if (!$user->img_user || $request->input('img_user') !== $user->img_user->file_name) {
                $user->addMedia(storage_path('tmp/uploads/' . $request->input('img_user')))->toMediaCollection('img_user');
            }
        } elseif ($user->img_user) {
            $user->img_user->delete();
        }

        if ($request->input('signature', false)) {
            if (!$user->signature || $request->input('signature') !== $user->signature->file_name) {
                $user->addMedia(storage_path('tmp/uploads/' . $request->input('signature')))->toMediaCollection('signature');
            }
        } elseif ($user->signature) {
            $user->signature->delete();
        }

        if ($request->input('stamp_signature', false)) {
            if (!$user->stamp_signature || $request->input('stamp_signature') !== $user->stamp_signature->file_name) {
                $user->addMedia(storage_path('tmp/uploads/' . $request->input('stamp_signature')))->toMediaCollection('stamp_signature');
            }
        } elseif ($user->stamp_signature) {
            $user->stamp_signature->delete();
        }

        return redirect(session('previous-url'));
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('team', 'jobtitle', 'roles', 'construction_contracts', 'issuebyRfas', 'assignRfas', 'commentByRfas', 'informationByRfas', 'createByUserRfas', 'updateByUserRfas', 'approveByUserRfas', 'createByUserTasks', 'userUserAlerts');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}