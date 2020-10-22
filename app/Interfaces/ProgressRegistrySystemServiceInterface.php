<?php


namespace App\Interfaces;


use phpDocumentor\Reflection\Types\Collection;

interface ProgressRegistrySystemServiceInterface
{
    public function getAllWorkPlaceLearningPeriods(): Collection;
    public function getAllLearningActivityActing(): Collection;
    public function getAllWorkPlaces(): Collection;
    public function getAllTimeslots(): Collection;
    public function getAllSavedLearningItems(): Collection;
}