<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new UserResource(User::with(['team', 'jobtitle', 'roles', 'construction_contracts'])->get());
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));
        $user->construction_contracts()->sync($request->input('construction_contracts', []));

        if ($request->input('img_user', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . $request->input('img_user')))->toMediaCollection('img_user');
        }

        if ($request->input('signature', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . $request->input('signature')))->toMediaCollection('signature');
        }

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new UserResource($user->load(['team', 'jobtitle', 'roles', 'construction_contracts']));
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

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
