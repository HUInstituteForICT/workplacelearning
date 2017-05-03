<?php

namespace App\Http\Controllers;

use App\Analysis;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{

    private $CACHE_KEY = 'analysis';
    private $analysis;

    /**
     * AnalysisController constructor.
     * @param \App\Analysis $analysis
     */
    public function __construct(\App\Analysis $analysis)
    {
        // We do this dependency injection so it's easier to mock during tests
        $this->analysis = $analysis;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $analyses = $this->analysis->all();
        return view('pages.analyses.index', compact('analyses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pages.analyses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $analysis = new $this->analysis($data);

        if (!$analysis->save())
            return redirect()
                ->back()
                ->withErrors(['error', "Failed to save the analysis to the database."]);

        return redirect()->route('analyses-show', $analysis->id)->with('success', 'The analysis has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $analysis = $this->analysis->findOrFail($id);
        $analysis_result = null;

        if (!\Cache::has(Analysis::CACHE_KEY . $analysis->id))
            $analysis->refresh();
        $analysis_result = \Cache::get(Analysis::CACHE_KEY . $analysis->id);

        return view('pages.analyses.show', compact('analysis', 'analysis_result'));
    }

    /**
     * Expire a cached analys
     *
     * @param Request $request
     * @return Response
     */
    public function expire(Request $request)
    {
        $data = $request->all();
        $analysis = $this->analysis->findOrFail($data['id']);
        $analysis->refresh();
        return redirect()->back()->with('sucess', 'Query has been removed from the cache');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {

    }

}

?>