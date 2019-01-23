<?php

namespace App\Repository\Eloquent;

use App\Chain;
use App\Student;

class ChainRepository
{
    public function get(int $id): Chain
    {
        return Chain::findOrFail($id);
    }

    public function save(Chain $chain): bool
    {
        return $chain->save();
    }

    /**
     * @return Chain[]
     */
    public function chainsAvailableForStudent(Student $student): array
    {
        return $student->getCurrentWorkplaceLearningPeriod()->chains()->orderBy('status', 'ASC')->with('activities')->get()->all();
    }
}
