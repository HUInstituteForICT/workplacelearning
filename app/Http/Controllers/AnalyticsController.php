<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Analysis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AnalyticsController extends Controller
{
    private $analysis;

    /**
     * AnalysisController constructor.
     */
    public function __construct(Analysis $analysis)
    {
        // We do this dependency injection so it's easier to mock during tests
        $this->analysis = $analysis;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $analyses = $this->analysis->all();

        return view('pages.analytics.index', compact('analyses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.analytics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $analysis = new $this->analysis($data);

        if (!$analysis->save()) {
            return redirect()
                ->back()
                ->withErrors(['error', __('analysis.create-error')]);
        }

        return redirect()->route('analytics-show', $analysis->id)->with('success', __('analysis.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $analysis = $this->analysis->findOrFail($id);
        $analysis_result = null;

        if (!\Cache::has(Analysis::CACHE_KEY.$analysis->id)) {
            $analysis->refresh();
        }
        $analysis_result = \Cache::get(Analysis::CACHE_KEY.$analysis->id);

        return view('pages.analytics.show', compact('analysis', 'analysis_result'));
    }

    public function export($id)
    {
        $analysis = $this->analysis->findOrFail($id);
        $data = null;

        if (!\Cache::has(Analysis::CACHE_KEY.$analysis->id)) {
            $analysis->refresh();
        }

        $data = \Cache::get(Analysis::CACHE_KEY.$analysis->id);

        if (isset($data['error']) && $data === null) {
            return abort(404);
        }

        /*
         * Looks like an unused route...?
         */
//        $d = Excel::create('Analyse data', function ($excel) use ($data): void {
//            $excel->sheet('New sheet', function ($sheet) use ($data): void {
//                $sheet->loadView('pages.analytics.export', compact('data'));
//            });
//        });

//        return $d->export('csv');

        return null;
    }

    /**
     * Expire a cached analys.
     */
    public function expire(Request $request)
    {
        $data = $request->all();
        $analysis = $this->analysis->findOrFail($data['id']);
        $analysis->refresh();

        return redirect()->back()->with('success', __('analysis.query-data-expired'));
    }

    /**
     * Expire all cached analyses.
     */
    public function expireAll()
    {
        $this->analysis->all()->each(function (Analysis $analysis): void {
            $analysis->refresh();
        });

        return redirect()->route('analytics-index')->with('success', __('analysis.query-data-expired'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $analysis = $this->analysis->findOrFail($id);
        $analysis_result = null;

        if (!\Cache::has(Analysis::CACHE_KEY.$analysis->id)) {
            $analysis->refresh();
        }
        $analysis_result = \Cache::get(Analysis::CACHE_KEY.$analysis->id);

        return view('pages.analytics.edit', compact('analysis', 'analysis_result'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'           => 'required',
            'query'          => 'required',
            'cache_duration' => 'required',
            'type_time'      => 'required',
        ]);

        /** @var Analysis $analysis */
        $analysis = $this->analysis->findOrFail($id);

        try {
            DB::select($request->get('query'));
        } catch (\Exception $exception) {
            return redirect()->back()->withInput()->withErrors(['Query cannot be executed: '.$exception->getMessage()]);
        }

        $analysis->name = Input::get('name');
        $analysis->query = Input::get('query');
        $analysis->cache_duration = Input::get('cache_duration');
        $analysis->type_time = Input::get('type_time');

        if (!$analysis->save()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['The analysis has not been updated']);
        }

        $analysis->refresh();

        return redirect()->action('AnalyticsController@show', [$analysis['id']])
            ->with('success', 'The analysis has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id): RedirectResponse
    {
        $analysis = $this->analysis->findOrFail($id);
        if (!$analysis->delete()) {
            return redirect()
                ->back()
                ->withErrors(['error', __('analysis.remove-error')]);
        }

        return redirect()->route('analytics-index')
            ->with('success', __('analysis.removed'));
    }
}
