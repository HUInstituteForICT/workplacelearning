<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|



*/

use App\Http\Middleware\CheckUserLevel;

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');
Route::post('locale', 'LocaleSwitcher@switchLocale')->name('localeswitcher');

// API ROUTES - NOTE: NO LOCALIZATION AS IT WILL BREAK THE REQUEST DUE TO REDIRECTS (urls without a specified language get redirected, breaks POST requests to GET)
Route::group(['before' => 'auth', 'middleware' => CheckUserLevel::class, 'prefix' => '/education-programs/api'],
    function () {
        // "API" for edu programs routes
        Route::get('education-programs', 'EducationProgramsController@getEducationPrograms');
        Route::post('education-program', 'EducationProgramsController@createEducationProgram');
        Route::delete('education-program/{program}', 'EducationProgramsController@deleteEducationProgram');

        Route::post('education-program/{program}/cohort/create', 'EducationProgramsController@createCohort');
        Route::put('education-program/cohort/{cohort}/update', 'EducationProgramsController@updateCohort');
        Route::get('education-program/cohort/{cohort}/clone', 'EducationProgramsController@cloneCohort');
        Route::get('education-program/cohort/{cohort}', 'EducationProgramsController@getCohort');
        Route::delete('education-program/cohort/{cohort}', 'EducationProgramsController@deleteCohort');
        Route::get('education-program/cohort/{cohort}/disable', 'EducationProgramsController@toggleDisabledCohort');

        Route::post('education-program/{cohort}/entity', 'EducationProgramsController@createEntity');
        Route::post('cohort/entity/{entity}/delete', 'EducationProgramsController@deleteEntity');

        Route::put('education-program/entity/{entity}', 'EducationProgramsController@updateEntity');
        Route::put('education-program/{program}', 'EducationProgramsController@updateProgram');
        Route::get('education-program/cohort/{cohort}/competence-description/remove',
            'EducationProgramsController@removeCompetenceDescription');
        Route::post('education-program/cohort/{cohort}/competence-description',
            'EducationProgramsController@createCompetenceDescription');
        Route::get('editable-education-program/{program}', 'EducationProgramsController@getEditableProgram');

        Route::get('education-program/{program}/disable', 'EducationProgramsController@toggleDisabled');
    }
);

Route::get('/manage/tips', function () {
    return view('pages.tips.tips-app');
})->middleware(['auth', CheckUserLevel::class])->name('tips-app');

Route::group(['middleware' => ['auth', CheckUserLevel::class], 'prefix' => '/api/'], function () {
    Route::resource('tip-coupled-statistics', 'TipApi\TipCoupledStatisticController');
    Route::resource('statistics', 'TipApi\StatisticController');
    Route::resource('tips', 'TipApi\TipsController');

    Route::post('moments/create/{tip}', 'TipApi\MomentController@create');
    Route::put('moments/{moment}', 'TipApi\MomentController@update');
    Route::delete('moments/{moment}', 'TipApi\MomentController@delete');

    Route::put('tips/{tip}/cohorts', 'TipApi\TipsController@updateCohorts')->name('tips.updateCohorts');
});

// Admin
Route::get('/reactlogs', 'ReactLogController@index')->middleware(CheckUserLevel::class)->name('reactlogs');
Route::get('/reactlogs/{reactLog}/fix', 'ReactLogController@fix')->middleware(CheckUserLevel::class)->name('fix-reactlog');

Route::group(['before' => 'auth'], function () {
    Route::post('/activity-export-mail', 'ActivityExportController@exportMail')->middleware('throttle:3,1');
    Route::post('/activity-export-doc', 'ActivityExportController@exportActivitiesToWord');
    Route::get('/download/activity-export-doc/{fileName}', 'ActivityExportController@downloadWordExport')->name('docx-export-download');
    // Catch the stat registration post
    Route::post('/log', 'LogController@log');
    Route::post('/reactlog', 'ReactLogController@store');
});

Route::group([
    'before' => ['auth'],
    'middleware' => ['usernotifications'],
], function () {
    Route::group(['middleware' => CheckUserLevel::class], function () {
        Route::get('/education-programs', 'EducationProgramsController@index')
            ->name('education-programs');

        Route::group(['prefix' => '/dashboard'], function () {
            Route::get('/', 'AnalyticsDashboardController@index')->name('dashboard.index');
            Route::get('/add', 'AnalyticsDashboardController@add')->name('dashboard.add');
            Route::post('/add', 'AnalyticsDashboardController@store')->name('dashboard.save');
            Route::post('/move/{id}/{oldpos}/{newpos}', 'AnalyticsDashboardController@move')->name('dashboard.move');
            Route::delete('/delete/{id}', 'AnalyticsDashboardController@destroy')->name('dashboard.delete');

            Route::get('/analytics/expire', 'AnalyticsController@expireAll')->name('analytics-expire-all');
            Route::get('/analytics', 'AnalyticsController@index')->name('analytics-index');
            Route::get('/analytics/view/{id}', 'AnalyticsController@show')->name('analytics-show');
            Route::get('/create', 'AnalyticsController@create')->name('analytics-create');
            Route::get('/edit/{id}', 'AnalyticsController@edit')->name('analytics-edit');
            Route::put('/update/{id}', 'AnalyticsController@update')->name('analytics-update');
            Route::post('/create', 'AnalyticsController@store')->name('analytics-store');
            Route::post('/expire', 'AnalyticsController@expire')->name('analytics-expire');
            Route::get('/export/{id}', 'AnalyticsController@export')->name('analytics-export');
            Route::delete('/destroy/{id}', 'AnalyticsController@destroy')->name('analytics-destroy');
            Route::resource('charts', 'AnalyticsChartController');
            Route::post('charts/create', 'AnalyticsChartController@create_step_2')->name('charts.create_step_2');

            Route::get('/chart_details/{id}/{label}', function ($id, $label) {
                $label = str_replace('_', ' ', $label);
                $idLabel = $id.';'.$label;

                return View::make('pages.analytics.dashboard.chart_details', compact('label', 'idLabel'));
            });

            Route::group(['prefix' => 'api'], function () {
                Route::get('chart_details/{label?}', 'AnalyticsChartController@getChartDetails')->name('charts-details');
                Route::get('column_values/{table?}/{column?}', 'QueryBuilderController@getColumnValues')->name('column-values');
            });

            Route::get('/builder/step/{id}', 'QueryBuilderController@showStep')->name('querybuilder.get');
            Route::post('/builder/step/{id}', 'QueryBuilderController@saveStep')->name('querybuilder.post');
            Route::get('/builder/tables', 'QueryBuilderController@getTables')->name('querybuilder.tables');
            Route::get('/builder/columns/{table?}', 'QueryBuilderController@getColumns')->name('querybuilder.columns');
            Route::get('/builder/relations/{model}', 'QueryBuilderController@getRelations')->name('querybuilder.relations');
            Route::post('/builder/query', 'QueryBuilderController@executeQuery')->name('querybuilder.query');
            Route::post('/builder/chart', 'QueryBuilderController@getChart')->name('querybuilder.chart');
            Route::post('/builder/testQuery', 'QueryBuilderController@testQuery')->name('querybuilder.test');
        });

        Route::group(['prefix' => 'template'], function () {
            Route::get('/', 'TemplateDashboardController@index')->name('template.index');
            Route::get('/view/{id}', 'TemplateDashboardController@show')->name('template.show')->where('id', '[0-9]+');
            Route::post('/{id}', 'TemplateDashboardController@update')->name('template.update')->where('id', '[0-9]+');
            Route::post('/', 'TemplateDashboardController@save')->name('template.save');
            Route::get('/create', 'TemplateDashboardController@create')->name('template.create');
            Route::delete('/{id}', 'TemplateDashboardController@destroy')->name('template.destroy')->where('id', '[0-9]+');

            Route::group(['prefix' => 'api'], function () {
                // Api routes
                Route::get('tables', 'TemplateDashboardController@getTables')->name('template.tables');
                Route::get('columns/{id?}', 'TemplateDashboardController@getColumns')->name('template.columns');
                Route::get('parameters/{id?}', 'TemplateDashboardController@getParameters')->name('template.parameters');
                Route::get('param_html/{id?}', 'TemplateDashboardController@getHTML')->name('template.param-html');
            });
        });
    });

    // User Creation and modification
    Route::get('profiel', 'ProfileController@show')->name('profile');
    Route::post('profiel/update', 'ProfileController@update');
    Route::put('profiel/change-password', 'ProfileController@changePassword');

    // Category updating
    Route::post('categorie/update/{id}',
        'ProducingWorkplaceLearningController@updateCategories')->name('categories-update')->where('id', '[0-9]*');
    // Learning Goal updating
    Route::post('learninggoal/update/{id}',
        'ActingWorkplaceLearningController@updateLearningGoals')->name('learninggoals-update')->where('id', '[0-9]*');

    // Calendar Creation
    Route::get('deadline', 'CalendarController@show')->name('deadline');
    Route::post('deadline/create', 'CalendarController@create')->name('deadline-create');
    Route::post('deadline/update', 'CalendarController@update')->name('deadline-update');

    // Bugreport
    Route::get('bugreport', 'HomeController@showBugReport')->name('bugreport');
    Route::post('bugreport/create', 'HomeController@createBugReport')->name('bugreport-create');

    Route::group([
        'middleware' => ['taskTypeRedirect'],
    ], function () {
        /* Add all middleware redirected urls here */
        Route::get('/', 'HomeController@showHome')->name('default');
        Route::get('home', 'HomeController@showHome')->name('home');
        Route::get('process', 'ActingActivityController@show')->name('process');
        Route::get('progress', 'ProducingActivityController@progress')->name('progress');
        Route::get('analysis', 'ProducingActivityController@show')->name('analysis');
        Route::get('period/create', 'ProducingWorkplaceLearningController@show')->name('period');
        Route::get('period/edit/{id}', 'ProducingWorkplaceLearningController@edit')->name('period-edit')->where('id',
            '[0-9]*');
    });
    // Dashboard

    Route::group([
        'middleware' => ['taskTypeRedirect'],
    ], function () {
        /* Add all middleware redirected urls here */
        Route::get('/', 'HomeController@showHome')->name('default');
        Route::get('home', 'HomeController@showHome')->name('home');
        Route::get('process', 'ActingActivityController@show')->name('process');
        Route::get('progress/{page}', 'ProducingActivityController@progress')->where('page', '[1-9]{1}[0-9]*')->name('progress');
        Route::get('analysis', 'ProducingActivityController@show')->name('analysis');
        Route::get('period/create', 'ProducingWorkplaceLearningController@show')->name('period');
        Route::get('period/edit/{id}', 'ProducingWorkplaceLearningController@edit')->name('period-edit')->where('id', '[0-9]*');
    });

    Route::get('/tip/{tip}/like', 'TipApi\TipsController@likeTip')->name('tips.like');

    /* EP Type: Acting */
    Route::group([
        'prefix' => '/acting',
    ], function () {
        Route::get('home', 'HomeController@showActingTemplate')->name('home-acting');
        Route::get('process', 'ActingActivityController@show')->name('process-acting');
        Route::post('process/create', 'ActingActivityController@create')->name('process-acting-create');
        Route::get('process/edit/{id}', 'ActingActivityController@edit')->name('process-acting-edit');
        Route::post('process/update/{id}', 'ActingActivityController@update')->name('process-acting-update');
        Route::get('process/delete/{activity}', 'ActingActivityController@delete')->name('process-acting-delete');

        Route::get('progress', 'ActingActivityController@progress')->name('progress-acting');

        // Internships & Internship Periods
        Route::get('period/create', 'ActingWorkplaceLearningController@show')->name('period-acting');
        Route::get('period/edit/{id}',
            'ActingWorkplaceLearningController@edit')->name('period-acting-edit')->where('id', '[0-9]*');
        Route::post('period/create', 'ActingWorkplaceLearningController@create')->name('period-acting-create');
        Route::post('period/update/{id}',
            'ActingWorkplaceLearningController@update')->name('period-acting-update')->where('id', '[0-9]*');

        // Report Creation
        Route::get('analysis', 'ActingAnalysisController@showChoiceScreen')->name('analysis-acting-choice');
        Route::get('analysis/{year}/{month}',
            'ActingAnalysisController@showDetail')->name('analysis-acting-detail');

        // Download competence description
        Route::get('competence-description/{competenceDescription}',
            function (\App\CompetenceDescription $competenceDescription) {
                return response()->download(storage_path('app/'.$competenceDescription->file_name),
                    'competence-description.pdf');
            })->name('competence-description');

        Route::get('evidence/{learningActivity}/remove', function (\App\LearningActivityActing $learningActivity) {
            if ($learningActivity->workplaceLearningPeriod->student->student_id != Auth::user()->student_id) {
                throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
            }

            if (Storage::exists("activity-evidence/{$learningActivity->evidence_disk_filename}")) {
                Storage::delete("activity-evidence/{$learningActivity->evidence_disk_filename}");
                $learningActivity->evidence_filename = null;
                $learningActivity->evidence_disk_filename = null;
                $learningActivity->evidence_mime = null;
                $learningActivity->save();
            }

            return redirect()->route('process-acting-edit', ['id' => $learningActivity->laa_id]);
        })->name('evidence-remove');

        Route::get('evidence/{learningActivity}/{diskFileName}',
            function (\App\LearningActivityActing $learningActivity, $diskFileName) {
                if ($learningActivity->evidence_disk_filename !== $diskFileName) {
                    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
                }

                return response()->download(storage_path('app/activity-evidence/'.$learningActivity->evidence_disk_filename),
                    $learningActivity->evidence_filename);
            })->name('evidence-download');
    });

    /* EP Type: Producing */
    Route::group([
        'prefix' => '/producing',
    ], function () {
        Route::get('home', 'HomeController@showProducingTemplate')->name('home-producing');

        Route::get('process', 'ProducingActivityController@show')
            ->middleware('can:create,App\LearningActivityActing')
            ->name('process-producing');

        Route::post('process/create', 'ProducingActivityController@create')
            ->middleware('can:create,App\LearningActivityActing')
            ->name('process-producing-create');

        Route::get('process/edit/{learningActivityProducing}', 'ProducingActivityController@edit')
            ->middleware('can:update,learningActivityProducing')
            ->name('process-producing-edit');

        Route::post('process/update/{learningActivityProducing}', 'ProducingActivityController@update')
            ->middleware('can:update,learningActivityProducing')
            ->name('process-producing-update');

        Route::get('process/delete/{learningActivityProducing}', 'ProducingActivityController@delete')
            ->middleware('can:delete,learningActivityProducing')
            ->name('process-producing-delete');

        // Progress
        Route::get('progress', 'ProducingActivityController@progress')->name('progress-producing');
        Route::get('report/export', 'ProducingReportController@wordExport')->name('report-producing-export');

        // Report Creation
        Route::get('analysis', 'ProducingAnalysisController@showChoiceScreen')->name('analysis-producing-choice');
        Route::get('analysis/{year}/{month}',
            'ProducingAnalysisController@showDetail')->name('analysis-producing-detail');

        // Feedback
        Route::get('feedback/{feedback}', 'FeedbackController@show')
            ->middleware('can:view,feedback')
            ->name('feedback-producing');

        Route::post('feedback/update/{feedback}', 'FeedbackController@update')
            ->middleware('can:update,feedback')
            ->name('feedback-producing-update');

        // Internships & Internship Periods
        Route::get('period/create', 'ProducingWorkplaceLearningController@show')->name('period-producing');
        Route::get('period/edit/{id}',
            'ProducingWorkplaceLearningController@edit')->name('period-producing-edit')->where('id', '[0-9]*');
        Route::post('period/create', 'ProducingWorkplaceLearningController@create')->name('period-producing-create');
        Route::post('period/update/{id}',
            'ProducingWorkplaceLearningController@update')->name('period-producing-update')->where('id', '[0-9]*');

        Route::post('/chain/create', 'ChainController@create')->name('chain-create');
        Route::put('/chain/{chain}', 'ChainController@save')->name('chain-save');
        Route::get('/chain/{chain}/delete', 'ChainController@delete')->name('chain-delete');
    });
});
