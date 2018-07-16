<?php


namespace App\Http\Controllers\TipApi;

use App\Http\Controllers\Controller;
use App\Http\Requests\MomentCreateRequest;
use App\Http\Requests\MomentUpdateRequest;
use App\Tips\Models\Moment;
use App\Tips\Models\Tip;
use App\Tips\Services\MomentManager;

class MomentController extends Controller
{
    public function create(Tip $tip, MomentManager $momentManager, MomentCreateRequest $request)
    {
        $moment=  $momentManager->createForTip($tip, $request);
        return response()->json($moment);
    }

    public function update(Moment $moment, MomentManager $momentManager, MomentUpdateRequest $request)
    {
        $moment = $momentManager->updateMoment($moment, $request);
        return response()->json($moment);
    }

    public function delete(Moment $moment, MomentManager $momentManager)
    {
        $momentManager->deleteMoment($moment);

        return response()->json([]);
    }
}
