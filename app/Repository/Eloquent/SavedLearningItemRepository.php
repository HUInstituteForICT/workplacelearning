<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Services\EvidenceFileHandler;
use App\UserSetting;
use App\SavedLearningItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SavedLearningItemRepository
{
    /**
     * @var EvidenceFileHandler
     */
    private $evidenceFileHandler;

    public function __construct(EvidenceFileHandler $evidenceFileHandler)
    {
        $this->evidenceFileHandler = $evidenceFileHandler;
    }

    public function all()
    {
        return SavedLearningItem::all();
    }

    public function findByStudentnr(int $id)
    {
        return SavedLearningItem::where('student_id', $id)->get();;
    }
}