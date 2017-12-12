<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


$factory->define(\App\Tips\CollectedDataStatisticVariable::class, function () {
    return [
        'type'                => 'collecteddatastatistic',
        'name'                => 'Total learning activities',
        'dataUnitMethod'      => 'totalLearningActivities',
        'dataUnitParameter'   => null,
        'nested_statistic_id' => null,
    ];
});

$factory->define(\App\Tips\Statistic::class, function () {
    return [
        'operator'                  => \App\Tips\Statistic::OPERATOR_ADD,
        'name'                      => 'Total learning activity + Total learning activity',
        'education_program_type_id'  => function () {
            return factory(\App\EducationProgramType::class)->states('acting')->create()->eptype_id;
        },
        'statistic_variable_one_id' => function () {
            return factory(\App\Tips\CollectedDataStatisticVariable::class)->create()->id;
        },
        'statistic_variable_two_id' => function () {
            return factory(\App\Tips\CollectedDataStatisticVariable::class)->create()->id;
        },
    ];
});

$factory->define(\App\Tips\Tip::class, function () {
    return [
        'name'           => 'Total learning activities times 2',
        'tipText'        => 'Your total learning activities are :percentage',
        'showInAnalysis' => true,
    ];
});

$factory->define(\App\EducationProgramType::class, function () {
    return ['eptype_id' => null, 'eptype_name' => null];
});

$factory->state(\App\EducationProgramType::class, 'acting', function () {
    return ['eptype_id' => 1, 'eptype_name' => 'Acting'];
});
$factory->state(\App\EducationProgramType::class, 'producing', function () {
    return ['eptype_id' => 2, 'eptype_name' => 'Producing'];
});