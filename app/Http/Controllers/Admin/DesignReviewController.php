<?php

namespace App\Http\Controllers\Admin;

use App\DesignReview;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDesignReviewRequest;
use App\Http\Requests\StoreDesignReviewRequest;
use App\Http\Requests\UpdateDesignReviewRequest;
use App\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DesignReviewController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('design_review_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DesignReview::with(['team'])->select(sprintf('%s.*', (new DesignReview)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'design_review_show';
                $editGate      = 'design_review_edit';
                $deleteGate    = 'design_review_delete';
                $crudRoutePart = 'design-reviews';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('sheet_title', function ($row) {
                return $row->sheet_title ? $row->sheet_title : '';
            });
            $table->editColumn('url', function ($row) {
                $url_sheet[] = '<a style="font-size:12px" class="btn btn-default btn-success" href="'. $row->url .'" target="_blank">
                            View on GoogleSheet </a>';
                return implode(' ', $url_sheet);
            });

            $table->rawColumns(['actions', 'placeholder', 'url']);

            return $table->make(true);
        }

        $teams = Team::get();

        return view('admin.designReviews.index', compact('teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('design_review_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.designReviews.create');
    }

    public function store(StoreDesignReviewRequest $request)
    {
        $designReview = DesignReview::create($request->all());

        return redirect()->route('admin.design-reviews.index');
    }

    public function edit(DesignReview $designReview)
    {
        abort_if(Gate::denies('design_review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designReview->load('team');

        return view('admin.designReviews.edit', compact('designReview'));
    }

    public function update(UpdateDesignReviewRequest $request, DesignReview $designReview)
    {
        $designReview->update($request->all());

        return redirect()->route('admin.design-reviews.index');
    }

    public function show(DesignReview $designReview)
    {
        abort_if(Gate::denies('design_review_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designReview->load('team');

        return view('admin.designReviews.show', compact('designReview'));
    }

    public function destroy(DesignReview $designReview)
    {
        abort_if(Gate::denies('design_review_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designReview->delete();

        return back();
    }

    public function massDestroy(MassDestroyDesignReviewRequest $request)
    {
        $designReviews = DesignReview::find(request('ids'));

        foreach ($designReviews as $designReview) {
            $designReview->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
