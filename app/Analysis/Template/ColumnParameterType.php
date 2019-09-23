<?php
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:31.
 */

namespace App\Analysis\Template;

use Illuminate\Support\Facades\Schema;

class ColumnParameterType extends ParameterType
{
    public function __construct()
    {
        parent::__construct('Column', 1);
    }

    /*
     * types[0] = the parameterTable from the Template model.
     * types[1] = the chosen column
     * */
    public function isOfType(array $types)
    {
        return count($types) >= 2 && Schema::connection('dashboard')->hasTable($types[0])
            && Schema::connection('dashboard')->hasColumn($types[0], $types[1]);
    }

    public function getErrorMsg()
    {
        return __('template.error.table');
    }
}
