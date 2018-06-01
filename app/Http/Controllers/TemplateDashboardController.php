<?php

namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Http\Request;
use App\Template;
use Illuminate\Support\Facades\Lang;
use App\Analysis\Template\ParameterManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemplateDashboardController extends Controller
{

    private $paramManager;

    public function __construct()
    {
        $this->paramManager = new ParameterManager();
    }

    public function index()
    {
        $templates = Template::all();
        return view('pages.analytics.template.dashboard', compact('templates'));
    }

    public function create()
    {
        $paramTypes = $this->paramManager->getAllTypes();
        $typeNames = array_map(function ($type) {
            return $type->getName();
        }, $paramTypes);
        return view('pages.analytics.template.create_template', compact('paramTypes', 'typeNames'));
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
        $data = $request->input('data');

        $this->validate($request, [
            'name' => 'required',
            'query' => 'required',
        ]);

        if ($data == null) {
            return redirect()
                ->back()
                ->withErrors([Lang::get('template.no_parameters')]);
        }

        $name = $request->input('name');
        $query = $request->input('query');

        $template = new Template(['name' => $name, 'query' => $query]);
        $template->save();

        foreach ($data as $values) {
            while (count($values) < 4) {
                array_push($values, null);
            }
            $values = array_values($values);

            $parameter = new Parameter([
                'name' => $values[0],
                'type_name' => $values[1],
                'table' => $values[2],
                'column' => $values[3]
            ]);
            $template->parameters()->save($parameter);
        }

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
            'query' => $query])) {
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

        return redirect()->route('template.index')
            ->with('success', Lang::get('template.template_removed'));
    }

    public function getTables()
    {
        $tables = DB::connection('dashboard')->select('SHOW TABLES');

        $tableNames = array_map(function ($object) {
            return $object->{'Tables_in_' . DB::connection('dashboard')->getDatabaseName()};
        }, $tables);

        return $tableNames;
    }

    public function getColumns($table)
    {
        return DB::connection('dashboard')->getSchemaBuilder()->getColumnListing($table);
    }

}
