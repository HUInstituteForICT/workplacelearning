<?php
namespace App\Repository;

use App\Tips\Tip;

interface TipRepositoryInterface
{
    public function get(int $id): Tip;

    public function save(Tip $tip);

}