<?php
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:32.
 */

namespace App\Analysis\Template;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ColumnValueParameterType extends ParameterType
{
    public function __construct()
    {
        parent::__construct('Column Value', 2);
    }

    /*
     * types[0] = the parameterTable from the Template model.
     * types[1] = the column
     * types[2] = the value chosen by the user
     * */
    public function isOfType(array $types)
    {
        if (count($types) < 3 || !Schema::connection('dashboard')->hasTable($types[0])
            || !Schema::connection('dashboard')->hasColumn($types[0], $types[1])) {
            return false;
        }
        $result = DB::connection('dashboard')->select('select '.$types[1].' from '.$types[0].' where '.$types[1]." = '".$types[2]."'");

        return count($result) > 0;
    }

    public function getErrorMsg()
    {
        return __('template.error.column');
    }
}
