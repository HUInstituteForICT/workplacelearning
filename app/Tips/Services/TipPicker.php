<?php

declare(strict_types=1);

namespace App\Tips\Services;

use App\Repository\LikeRepositoryInterface;
use App\Repository\StudentTipViewRepositoryInterface;
use App\Services\CurrentUserResolver;
use App\Tips\EvaluatedTip;
use App\Tips\EvaluatedTipInterface;

class TipPicker
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var LikeRepositoryInterface
     */
    private $likeRepository;
    /**
     * @var StudentTipViewRepositoryInterface
     */
    private $studentTipViewRepository;

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        LikeRepositoryInterface $likeRepository,
        StudentTipViewRepositoryInterface $studentTipViewRepository
    ) {
        $this->currentUserResolver = $currentUserResolver;
        $this->likeRepository = $likeRepository;
        $this->studentTipViewRepository = $studentTipViewRepository;
    }

    /**
     * @return EvaluatedTip[]
     */
    public function pick(array $applicableTips, ?int $limit): array
    {
        $pickedTips = collect($applicableTips)->filter(function (EvaluatedTipInterface $evaluatedTip) {
            $this->likeRepository->loadForTipByStudent(
                $evaluatedTip->getTip(),
                $this->currentUserResolver->getCurrentUser()
            );

            // If not liked by this student yet, allow it to be shown
            if ($evaluatedTip->getTip()->likes->count() === 0) {
                return true;
            }

            // If liked, allow, if disliked filter it out
            return $evaluatedTip->getTip()->likes[0]->type === 1;
        })->shuffle();

        if ($limit !== null) {
            $pickedTips = $pickedTips->take($limit);
        }

        return $pickedTips->all();
    }

    public function markTipsViewed(array $evaluatedTips): void
    {
        array_walk($evaluatedTips, function (EvaluatedTipInterface $evaluatedTip): void {
            $this->studentTipViewRepository->createForTip(
                $evaluatedTip->getTip(),
                $this->currentUserResolver->getCurrentUser()
            );
        });
    }
}
