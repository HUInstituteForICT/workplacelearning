<?php

declare(strict_types=1);

namespace App\Tips\Services;

use App\Tips\Models\Moment;
use App\Tips\Models\Tip;
use Illuminate\Http\Request;

class MomentManager
{
    public function createForTip(Tip $tip, Request $request): Moment
    {
        $moment = new Moment();
        $moment->tip()->associate($tip);

        $this->updateMoment($moment, $request);

        return $moment;
    }

    public function updateMoment(Moment $moment, Request $request): Moment
    {
        $moment->rangeStart = $request->get('rangeStart');
        $moment->rangeEnd = $request->get('rangeEnd');

        $moment->save();

        return $moment;
    }

    /**
     * @throws \Exception
     */
    public function deleteMoment(Moment $moment): void
    {
        $moment->delete();
    }
}
