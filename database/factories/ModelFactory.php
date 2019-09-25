<?php

declare(strict_types=1);

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

use Carbon\Carbon;

$factory->define(\App\Student::class, function (Faker\Generator $faker) {
    return [
        'studentnr' => $faker->numberBetween(1000000, 9999999),
        'firstname' => $faker->firstName,
        'lastname'  => $faker->lastName,
        'ep_id'     => function () {
            return factory(\App\EducationProgram::class)->create()->ep_id;
        },
        'userlevel'                    => 0,
        'pw_hash'                      => Hash::make($faker->password),
        'gender'                       => $faker->randomElement(['m', 'f']),
        'birthdate'                    => $faker->date(),
        'email'                        => $faker->email,
        'phonenr'                      => $faker->phoneNumber,
        'locale'                       => 'en',
        'canvas_user_id'               => null,
        'is_registered_through_canvas' => false,
        'email_verified_at'            => date('Y-m-d H:i:s'),
    ];
});

$factory->define(\App\EducationProgram::class, function (Faker\Generator $faker) {
    return [
        'ep_name'   => $faker->name.' opleiding',
        'eptype_id' => function () {
            return factory(\App\EducationProgramType::class)->states('acting')->make()->eptype_id;
        },
        'disabled' => false,
    ];
});

$factory->define(\App\Tips\Models\StatisticVariable::class, function () {
    return [
        'filters' => \App\Tips\Models\StatisticVariable::$availableFilters['acting'],
    ];
});

$factory->define(\App\Tips\Models\CustomStatistic::class, function () {
    return [
        'operator'                  => \App\Tips\Models\CustomStatistic::OPERATOR_ADD,
        'name'                      => 'Total learning activity + Total learning activity',
        'education_program_type'    => 'acting',
        'select_type'               => 'count',
        'statistic_variable_one_id' => function () {
            return factory(\App\Tips\Models\StatisticVariable::class)->create()->id;
        },
        'statistic_variable_two_id' => function () {
            return factory(\App\Tips\Models\StatisticVariable::class)->create()->id;
        },
    ];
});

$factory->define(\App\Tips\Models\Tip::class, function () {
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

$factory->state(\App\EducationProgram::class, 'acting', function () {
    return [
        'eptype_id' => function () {
            return factory(\App\EducationProgramType::class)->states('acting')->create()->eptype_id;
        },
    ];
});

$factory->state(\App\EducationProgram::class, 'producing', function () {
    return [
        'eptype_id' => function () {return factory(\App\EducationProgramType::class)->states('producing')->create()->eptype_id; },
    ];
});

$factory->define(\App\WorkplaceLearningPeriod::class, function () {
    return [
        'wp_id' => function () {
            return factory(\App\Workplace::class)->create();
        },
        'nrofdays'        => 10,
        'description'     => 'Test description, thanks',
        'is_in_analytics' => true,
        'hours_per_day'   => 7.5,
    ];
});

$factory->define(\App\Workplace::class, function (Faker\Generator $faker) {
    return [
        'wp_name'           => $faker->lastName,
        'street'            => $faker->streetName,
        'housenr'           => $faker->buildingNumber,
        'postalcode'        => $faker->postcode,
        'town'              => $faker->city,
        'contact_name'      => $faker->firstName,
        'contact_email'     => $faker->email,
        'contact_phone'     => $faker->phoneNumber,
        'numberofemployees' => $faker->randomNumber(2),
        'country'           => $faker->country,
    ];
});

$factory->define(\App\Cohort::class, function (Faker\Generator $faker) {
    return [
        'name'        => $faker->userName,
        'description' => $faker->text,
        'disabled'    => false,
    ];
});

$factory->define(\App\Timeslot::class, function (Faker\Generator $faker) {
    return [
        'timeslot_text' => $faker->text(20),
    ];
});

$factory->define(\App\LearningActivityActing::class, function (Faker\Generator $faker) {
    return [
        'date'           => Carbon::now(),
        'situation'      => $faker->text(40), // gets saved in $laa->situation !!!!!!
        'lessonslearned' => 'A lot',
        'support_wp'     => 'Nothing',
        'support_ed'     => 'Nothing',
    ];
});

$factory->define(\App\ResourcePerson::class, function (Faker\Generator $faker) {
    return [
        'person_label' => $faker->name,
    ];
});

$factory->define(\App\ResourceMaterial::class, function (Faker\Generator $faker) {
    return [
        'rm_label' => $faker->word,
    ];
});
