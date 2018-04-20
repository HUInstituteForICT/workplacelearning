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

$factory->define(\App\Student::class, function(Faker\Generator $faker) {
    return [
        'studentnr' => $faker->numberBetween(1000000, 9999999),
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'ep_id' => function() {
            return factory(\App\EducationProgram::class)->create()->ep_id;
        },
        'userlevel' => 0,
        'pw_hash' => Hash::make($faker->password),
        'gender' => $faker->randomElement(['m', 'f']),
        'birthdate' => $faker->date(),
        'email' => $faker->email,
        'phonenr' => $faker->phoneNumber,
        'locale' => $faker->randomElement(['nl', 'en'])
    ];
});

$factory->define(\App\EducationProgram::class, function(Faker\Generator $faker) {
    return [
        'ep_name' => $faker->name . ' opleiding',
        'eptype_id' => function() {
            return factory(\App\EducationProgramType::class)->states('acting')->create()->eptype_id;
        },
        'disabled' => false,
    ];
});

$factory->define(\App\Tips\Statistics\StatisticVariable::class, function () {
    return [
        'type'      => 'acting',
        'filters'   => \App\Tips\Statistics\StatisticVariable::$availableFilters['acting'],
        'selectType' => 'count',
    ];
});

$factory->define(\App\Tips\Statistics\CustomStatistic::class, function () {
    return [
        'operator'                  => \App\Tips\Statistics\CustomStatistic::OPERATOR_ADD,
        'name'                      => 'Total learning activity + Total learning activity',
        'education_program_type_id'  => function () {
            return factory(\App\EducationProgramType::class)->states('acting')->create()->eptype_id;
        },
        'statistic_variable_one_id' => function () {
            return factory(\App\Tips\Statistics\StatisticVariable::class)->create()->id;
        },
        'statistic_variable_two_id' => function () {
            return factory(\App\Tips\Statistics\StatisticVariable::class)->create()->id;
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