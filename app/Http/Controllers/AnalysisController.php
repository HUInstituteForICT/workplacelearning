<?php

namespace App\Http\Controllers;

use App\Analysis;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $analyses = Analysis::all();
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
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $anal = new Analysis($data);
        // todo: Run analysis
        if (!$anal->save())
            return redirect()->back()->withErrors(['error', "Failed to save the analysis to the database."]);
        return redirect()->route('analysis-show', $anal->id)->with('success', 'The analysis has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $anal = Analysis::findOrFail($id);
        return view('pages.analyses.show', compact('anal'));
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