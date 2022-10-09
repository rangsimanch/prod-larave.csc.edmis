<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRecoveryDashboardRequest;
use App\Http\Requests\StoreRecoveryDashboardRequest;
use App\Http\Requests\UpdateRecoveryDashboardRequest;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecoveryDashboardController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('recovery_dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $counting_file = 0;
        $mime_type = 'application/pdf';
        $date_range = '2022-08-17';
        $model_rfa = 'App\Rfa';
        $query_media = DB::table('media')
                ->where('mime_type', '=', $mime_type)
                ->where('created_at', '<=', $date_range)
                ->where('model_type', '=', $model_rfa)
                ->pluck('id')->toArray();

        foreach($query_media as $id){
            if(file_exists(storage_path("/app" . "/public" . "/" . $id))){
               $counting_file += 1;
            }
        }
        

        return view('admin.recoveryDashboards.index',compact('counting_file'));
    }

    public function create()
    {
        abort_if(Gate::denies('recovery_dashboard_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recoveryDashboards.create');
    }

    public function store(StoreRecoveryDashboardRequest $request)
    {
        $recoveryDashboard = RecoveryDashboard::create($request->all());

        return redirect()->route('admin.recovery-dashboards.index');
    }

    public function edit(RecoveryDashboard $recoveryDashboard)
    {
        abort_if(Gate::denies('recovery_dashboard_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recoveryDashboards.edit', compact('recoveryDashboard'));
    }

    public function update(UpdateRecoveryDashboardRequest $request, RecoveryDashboard $recoveryDashboard)
    {
        $recoveryDashboard->update($request->all());

        return redirect()->route('admin.recovery-dashboards.index');
    }

    public function show(RecoveryDashboard $recoveryDashboard)
    {
        abort_if(Gate::denies('recovery_dashboard_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recoveryDashboards.show', compact('recoveryDashboard'));
    }

    public function destroy(RecoveryDashboard $recoveryDashboard)
    {
        abort_if(Gate::denies('recovery_dashboard_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recoveryDashboard->delete();

        return back();
    }

    public function massDestroy(MassDestroyRecoveryDashboardRequest $request)
    {
        RecoveryDashboard::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
