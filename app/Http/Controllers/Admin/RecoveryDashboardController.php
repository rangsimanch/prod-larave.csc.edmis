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
        $counting_all = 0;
        $date_range = '2022-08-17';
        $query_all = DB::table('media')
                ->where('created_at', '<=', $date_range)
                ->pluck('id')->toArray();
        $all_sum = number_format(count($query_all));
        foreach($query_all as $id){
            if(file_exists(storage_path("/app" . "/public" . "/" . $id))){
               $counting_all += 1;
            }
        }
        $count_all_format = number_format($counting_all);
        
        // PDF
        $counting_pdf = 0;
        $mime_type = 'application/pdf';
        $date_range = '2022-08-17';
        $query_pdf = DB::table('media')
                ->where('mime_type', '=', $mime_type)
                ->where('created_at', '<=', $date_range)
                ->pluck('id')->toArray();
    
        $pdf_sum = number_format(count($query_pdf));
        foreach($query_pdf as $id){
            if(file_exists(storage_path("/app" . "/public" . "/" . $id))){
               $counting_pdf += 1;
            }
        }
        $count_pdf_format = number_format($counting_pdf);

        // RFA
        $counting_rfa = 0;
        $model_type = 'App\Rfa';
        $query_rfa = DB::table('media')
                ->where('mime_type', '=', $mime_type)
                ->where('created_at', '<=', $date_range)
                ->where('model_type', '=', $model_type)
                ->pluck('id')->toArray();
    
        $rfa_sum = number_format(count($query_rfa));
        foreach($query_rfa as $id){
            if(file_exists(storage_path("/app" . "/public" . "/" . $id))){
               $counting_rfa += 1;
            }
        }
        $count_rfa_format = number_format($counting_rfa);

        // SRT
        $counting_srt = 0;
        $model_type = "%Srt%";
        $query_srt = DB::table('media')
                ->where('mime_type', '=', $mime_type)
                ->where('created_at', '<=', $date_range)
                ->where('model_type', 'LIKE', $model_type)
                ->pluck('id')->toArray();
    
        $srt_sum = number_format(count($query_srt));
        foreach($query_srt as $id){
            if(file_exists(storage_path("/app" . "/public" . "/" . $id))){
               $counting_srt += 1;
            }
        }
        $count_srt_format = number_format($counting_srt);

        //Other
        $other_sum = number_format(count($query_pdf) - (count($query_rfa) + count($query_srt)));
        $count_other_format = number_format($counting_pdf - ($counting_rfa + $counting_srt));


        return view('admin.recoveryDashboards.index',compact('count_pdf_format', 'pdf_sum', 'count_rfa_format', 'rfa_sum', 
        'count_srt_format', 'srt_sum', 'other_sum', 'count_other_format', 'all_sum', 'count_all_format'));
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
