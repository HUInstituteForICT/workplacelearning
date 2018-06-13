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
        return $this->show(null);
    }

    public function show($id)
    {
        $template = null;
        $parameters = [];

        $paramTypes = $this->paramManager->getAllTypes();
        $typeNames = array_map(function ($type) {
            return $type->getName();
        }, $paramTypes);

        if ($id != null) {
            $template = (new \App\Template)->findOrFail($id);
            $parameters = $template->getParameters();
            if ($parameters == null) {
                $parameters = [];
            }
        }
        return view('pages.analytics.template.create_template', compact('paramTypes', 'typeNames', 'template', 'parameters'));
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

        $templateID = $request->input('templateID');
        $name = $request->input('name');
        $description = $request->input('description');
        if ($description == null) {
            $description = "";
        }
        $query = $request->input('query');

        if ($templateID != null) {
            $template = (new \App\Template)->find($templateID);

            if ($template != null) {
                $template->update(['name' => $name, 'description' => $description]);
                $template->update(['name' => $name, 'description' => $description, 'query' => $query]);
                $this->saveParameters($data, $template);
            }

            return redirect()->action('TemplateDashboardController@index')
                ->with('success', Lang::get('template.template_updated'));
        }

        $template = new Template(['name' => $name, 'description' => $description, 'query' => $query]);
        $template->save();
        $this->saveParameters($data, $template);

        return redirect()->action('TemplateDashboardController@index')
            ->with('success', Lang::get('template.template_saved'));
    }

    private function saveParameters($data, $template)
    {
        $existingParams = $template->getParameters();

        if ($existingParams != null && count($existingParams) > count($data)) {
            $ids = array_map(function ($values) {
                $values = array_values($values);
                return intval($values[0]);
            }, $data);
            Log::debug($ids);

            foreach ($existingParams as $param) {
                $id = $param['id'];
                if (!in_array($id, $ids, false)) {
                    $param->delete();
                    Log::debug("Deleting:");
                    Log::debug(json_encode($param));
                }
            }
        }

        $parameters = [];
        foreach ($data as $values) {
            while (count($values) < 5) {
                array_push($values, null);
            }
            $values = array_values($values);

            $id = intval($values[0]);
            if ($id != null && $id > 0) {
                $existingPar = (new \App\Parameter)->find($id);
                if ($existingPar != null) {
                    $existingPar->update([
                        'name' => $values[1],
                        'type_name' => $values[2],
                        'table' => $values[3],
                        'column' => $values[4]
                    ]);
                    Log::debug('not null, updating');
                    continue;
                }

            } else {
                $parameter = new Parameter([
                    'id' => $values[0],
                    'template_id' => $template->id,
                    'name' => $values[1],
                    'type_name' => $values[2],
                    'table' => $values[3],
                    'column' => $values[4]
                ]);

                Log::debug(json_encode($parameter));
                array_push($parameters, $parameter);
            }
        }

        if (count($parameters) > 0) {
            $template->parameters()->saveMany($parameters);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'query' => 'required',
        ]);

        $name = $request->input('name');
        $description = $request->input('description', '');
        $query = $request->input('query');

        $template = (new \App\Template)->find($id);
        if (!$template->update(
            ['name' => $name,
                'description' => $description,
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

    public function getParameters($templateID)
    {
        $template = (new \App\Template)->find($templateID);
        if ($template == null) {
            return [];
        }
        return $template->getParameters();
    }

}
