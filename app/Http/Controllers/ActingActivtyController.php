<?php
namespace App\Http\Controllers;

use App\WorkplaceLearningPeriod;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ActingActivtyController extends Controller {
    public function show() {
        $resourcePersons = Auth::user()->getEducationProgram()->getResourcePersons()->union(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        return view('pages.actingactivity')
            ->with('timeslots', Auth::user()->getEducationProgram()->getTimeslots())
            ->with('learningWith', $resourcePersons)
            ->with('theory', Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', Auth::user()->getEducationProgram()->getCompetencies());
    }
}
