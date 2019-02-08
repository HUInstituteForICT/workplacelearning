<?php


namespace App\Tips\Statistics\Predefined;


use App\Category;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;
use Illuminate\Database\Eloquent\Builder;

class ProducingCategoryWithMostWithoutTheory extends BasePredefinedStatistic
{

    public function getName(): string
    {
        return 'Category with the most activities done alone (without theory or person)';
    }

    public function getResultDescription(): string
    {
        return 'The category\'s name';
    }

    public function calculate(): Resultable
    {
        $categoryData = $this->wherePeriod(

            $this->learningPeriod->learningActivityProducing()
                ->selectRaw('category_id, COUNT(category_id) as count')
                ->where(function (Builder $builder) {
                    $builder->whereNull('res_material_id')->whereNull('res_person_id');
                })
                ->limit(1)
                ->orderBy('count', 'DESC')
                ->groupBy('category_id')
                ->getBaseQuery()
        )->first();

        if (!$categoryData) {
            return new InvalidStatisticResult();
        }

        $totalActivities = $this->wherePeriod($this->learningPeriod->learningActivityProducing()->where('category_id',
            '=',
            $categoryData->category_id)->getBaseQuery())->count();

        if ($totalActivities === 0 || !$categoryData) {
            return new InvalidStatisticResult();
        }

        $category = Category::find($categoryData->category_id);

        $percentage = $categoryData->count / $totalActivities;

        return new StatisticCalculationResult($percentage, $category->localizedLabel());
    }

    public function getEducationProgramType(): string
    {
        return self::PRODUCING_TYPE;
    }
}