<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRecoveryDashboardRequest;
use App\Http\Requests\StoreRecoveryDashboardRequest;
use App\Http\Requests\UpdateRecoveryDashboardRequest;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;



class RecoveryDashboardController extends Controller
{
    public function index(Request $request)
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
        $rfa_array = array();
        $model_type = 'App\Rfa';
        $query_rfa = DB::table('media')
                ->join('rfas', 'media.model_id', '=', 'rfas.id')
                ->where('media.mime_type', '=', $mime_type)
                ->where('media.created_at', '<=', $date_range)
                ->where('media.model_type', '=', $model_type)
                ->whereNull('rfas.deleted_at')
                ->pluck('media.id')->toArray();
        $rfa_sum = number_format(count($query_rfa));
        $rfa_array = array();
        foreach($query_rfa as $id){
            if(file_exists(storage_path("/app" . "/public" . "/" . $id))){
               $counting_rfa += 1;         
            }
            else{
                array_push($rfa_array, $id);
            }
        }
        $count_rfa_format = number_format($counting_rfa);

        // RFA Table
        $query_rfa_table = DB::table('media')
        ->join('rfas', 'media.model_id', '=', 'rfas.id')
        ->join('construction_contracts','rfas.construction_contract_id', '=', 'construction_contracts.id')
        ->whereIn('media.id', $rfa_array)->select('media.model_id', 'rfas.title', 'rfas.document_number', 'rfas.origin_number', 'construction_contracts.code', 'media.collection_name', 'rfas.created_at')
        ->get();
        
        if ($request->ajax()) {
           
            $table = Datatables::of($query_rfa_table);

            $table->addColumn('placeholder', '');

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });

            $table->editColumn('document_number', function ($row) {
                return $row->document_number ? $row->document_number : '';
            });

            $table->editColumn('origin_number', function ($row) {
                return $row->origin_number ? $row->origin_number : '';
            });

            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });

            $table->editColumn('collection_name', function ($row) {
                if ($row->collection_name == 'file_upload_1'){
                    return 'Attach Files';
                }
                else if ($row->collection_name == 'submittals_file'){
                    return 'Submittals Files';
                }
                else if ($row->collection_name == 'work_file_upload' || $row->collection_name == 'commercial_file_upload'){
                    return 'Completed Files';
                }
                else{
                    return $row->collection_name ? $row->collection_name : '';
                }
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });

            $table->rawColumns(['model_id', 'title', 'document_number', 'origin_number', 'code', 'collection_name', 'created_at']);
            return $table->make(true);
        }

        if(Auth::id() != 1){
            $construction_contracts = ConstructionContract::where('id',session('construction_contract_id'))
            ->where('id', '!=', '15')->get();
        }
        else{
            $construction_contracts = ConstructionContract::where('id', '!=', '15')->get();
        }

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
        'count_srt_format', 'srt_sum', 'other_sum', 'count_other_format', 'all_sum', 'count_all_format','construction_contracts'));
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
