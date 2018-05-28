<?php
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:31
 */

namespace App\Analysis\Template;


class ColumnParameterType extends ParameterType
{

    public function __construct()
    {
        parent::__construct("Column", 1);
    }

    /*
     * types[0] = the parameterTable from the Template model.
     * types[1] = the chosen column
     * */
    public function isOfType(array $types)
    {
        //TODO: do sql query to check if the column exists.
    }

}