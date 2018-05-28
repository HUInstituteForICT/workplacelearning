<?php
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:32
 */

namespace App\Analysis\Template;


class ColumnValueParameterType extends ParameterType
{

    public function __construct()
    {
        parent::__construct("Column Value", 2);
    }

    /*
     * types[0] = the parameterTable from the Template model.
     * types[1] = the display column
     * types[2] = the value chosen by the user
     * */
    public function isOfType(array $types)
    {
        //TODO: do sql query to check if the column and display column exists.  return true if display column type == chosen type
    }

}