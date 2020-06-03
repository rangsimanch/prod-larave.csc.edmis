<?php

namespace App\Http\Controllers\Admin;

use App\ConstructionContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyInterimPaymentRequest;
use App\Http\Requests\StoreInterimPaymentRequest;
use App\Http\Requests\UpdateInterimPaymentRequest;
use App\InterimPayment;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Team;


class InterimPaymentController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('interim_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = InterimPayment::with(['construction_contract', 'team'])->select(sprintf('%s.*', (new InterimPayment)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'interim_payment_show';
                $editGate      = 'interim_payment_edit';
                $deleteGate    = 'interim_payment_delete';
                $crudRoutePart = 'interim-payments';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('payment_period', function ($row) {
                return $row->payment_period ? $row->payment_period : "";
            });
            $table->editColumn('month', function ($row) {
                return $row->month ? InterimPayment::MONTH_SELECT[$row->month] : '';
            });
            $table->editColumn('year', function ($row) {
                return $row->year ? InterimPayment::YEAR_SELECT[$row->year] : '';
            });
            $table->addColumn('construction_contract_code', function ($row) {
                return $row->construction_contract ? $row->construction_contract->code : '';
            });

            $table->editColumn('file_upload', function ($row) {
                if (!$row->file_upload) {
                    return '';
                }

                $links = [];

                foreach ($row->file_upload as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'construction_contract', 'file_upload']);

            return $table->make(true);
        }

        $construction_contracts = ConstructionContract::get()->pluck('code')->toArray();
        $teams                  = Team::get()->pluck('name')->toArray();

        return view('admin.interimPayments.index', compact('construction_contracts', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('interim_payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.interimPayments.create', compact('construction_contracts'));
    }

    public function store(StoreInterimPaymentRequest $request)
    {
        $interimPayment = InterimPayment::create($request->all());

        foreach ($request->input('file_upload', []) as $file) {
            $interimPayment->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $interimPayment->id]);
        }

        return redirect()->route('admin.interim-payments.index');
    }

    public function edit(InterimPayment $interimPayment)
    {
        abort_if(Gate::denies('interim_payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $construction_contracts = ConstructionContract::all()->pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $interimPayment->load('construction_contract', 'team');

        return view('admin.interimPayments.edit', compact('construction_contracts', 'interimPayment'));
    }

    public function update(UpdateInterimPaymentRequest $request, InterimPayment $interimPayment)
    {
        $interimPayment->update($request->all());

        if (count($interimPayment->file_upload) > 0) {
            foreach ($interimPayment->file_upload as $media) {
                if (!in_array($media->file_name, $request->input('file_upload', []))) {
                    $media->delete();
                }
            }
        }

        $media = $interimPayment->file_upload->pluck('file_name')->toArray();

        foreach ($request->input('file_upload', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $interimPayment->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('file_upload');
            }
        }

        return redirect()->route('admin.interim-payments.index');
    }

    public function show(InterimPayment $interimPayment)
    {
        abort_if(Gate::denies('interim_payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interimPayment->load('construction_contract', 'team');

        return view('admin.interimPayments.show', compact('interimPayment'));
    }

    public function destroy(InterimPayment $interimPayment)
    {
        abort_if(Gate::denies('interim_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $interimPayment->delete();

        return back();
    }

    public function massDestroy(MassDestroyInterimPaymentRequest $request)
    {
        InterimPayment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('interim_payment_create') && Gate::denies('interim_payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new InterimPayment();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
