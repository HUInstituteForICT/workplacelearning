<?php


namespace App\Tips;

/**
 * This interface only exists because the Single Table Inheritance package requires that our Parent class StatisticVariable is a concrete class and not an interface or abstract class.
 * This way we can still force our "concrete" implementations to have the getValue method
 *
 */
interface HasStatisticVariableValue
{
    /**
     * @return float|int the value of the data unit
     */
    public function getValue();
}