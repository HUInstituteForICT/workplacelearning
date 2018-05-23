<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Input;

class TemplateDashboardController extends Controller
{

    public function index()
    {
        $templates = Template::all();
        return view('pages.analytics.template.dashboard', compact('templates'));
    }

    public function create()
    {
        return view('pages.analytics.template.create_template');
    }

    public function show($id)
    {
        $template = (new \App\Template)->findOrFail($id);

        if ($template != null) {
            return view('pages.analytics.template.show_template', compact('template'));
        }
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'query' => 'required',
        ]);

        $name = $request->input('name');
        $query = $request->input('query');

        $template = new Template(['name' => $name, 'query' => $query]);
        $template->save();
        return redirect()->action('TemplateDashboardController@index')
            ->with('success', Lang::get('template.template_saved'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'query' => 'required',
        ]);

        $name = $request->input('name');
        $query = $request->input('query');

        $template = (new \App\Template)->find($id);
        if (!$template->update(['name' => $name,
            'query' => $query ])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Lang::get('template.template_not_updated')]);
        }

        $template->refresh();
        return redirect()->action('TemplateDashboardController@show', [$template['id']])
            ->with('success', Lang::get('template.template_updated'));
    }


    public function destroy($id)
    {
        $template = (new \App\Template)->find($id);
        try {
            $template->delete();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors([Lang::get('template.template_not_removed')]);
        }

        //TODO: change route
        return redirect()->route('template.index')
            ->with('success', Lang::get('template.template_removed'));
    }

}
