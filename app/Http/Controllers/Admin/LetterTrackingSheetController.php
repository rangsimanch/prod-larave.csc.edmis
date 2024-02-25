<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLetterTrackingSheetRequest;
use App\Http\Requests\StoreLetterTrackingSheetRequest;
use App\Http\Requests\UpdateLetterTrackingSheetRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LetterTrackingSheetController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('letter_tracking_sheet_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.letterTrackingSheets.index');
    }

    public function create()
    {
        abort_if(Gate::denies('letter_tracking_sheet_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.letterTrackingSheets.create');
    }

    public function store(StoreLetterTrackingSheetRequest $request)
    {
        $letterTrackingSheet = LetterTrackingSheet::create($request->all());

        return redirect()->route('admin.letter-tracking-sheets.index');
    }

    public function edit(LetterTrackingSheet $letterTrackingSheet)
    {
        abort_if(Gate::denies('letter_tracking_sheet_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.letterTrackingSheets.edit', compact('letterTrackingSheet'));
    }

    public function update(UpdateLetterTrackingSheetRequest $request, LetterTrackingSheet $letterTrackingSheet)
    {
        $letterTrackingSheet->update($request->all());

        return redirect()->route('admin.letter-tracking-sheets.index');
    }

    public function show(LetterTrackingSheet $letterTrackingSheet)
    {
        abort_if(Gate::denies('letter_tracking_sheet_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.letterTrackingSheets.show', compact('letterTrackingSheet'));
    }

    public function destroy(LetterTrackingSheet $letterTrackingSheet)
    {
        abort_if(Gate::denies('letter_tracking_sheet_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $letterTrackingSheet->delete();

        return back();
    }

    public function massDestroy(MassDestroyLetterTrackingSheetRequest $request)
    {
        $letterTrackingSheets = LetterTrackingSheet::find(request('ids'));

        foreach ($letterTrackingSheets as $letterTrackingSheet) {
            $letterTrackingSheet->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
