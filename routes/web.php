<?php

declare(strict_types=1);

use App\Http\Middleware\RequireActiveInternship;
use App\Http\Middleware\RequiresAdminLevel;
use App\Http\Middleware\RequiresTeacherLevel;
use App\Services\CurrentUserResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

Route::post('/canvas', 'CanvasLTIController');
Route::match(['get', 'post'], '/canvas/register',
    'CanvasRegistrationController')->middleware('guest')->name('canvas-registration');
Route::post('locale', 'LocaleSwitcher@switchLocale')->name('localeswitcher');

// Evidence can be a public route because we use a UUID and the id of the evidence entity. Random access is next to impossible
Route::get('evidence/{evidence}/{diskFileName}', 'EvidenceController@download')->name('evidence-download');

// General user routes
Route::get('/logout', 'Auth\LoginController@logout')->middleware('auth');

// Routes for non-students
Route::middleware(['auth', 'verified'])->group(static function (): void {
    // outside prefix because of namespace issues
    Route::get('teacher/home', 'HomeController@showTeacherTemplate')->name('home-teacher');
    Route::middleware(RequiresTeacherLevel::class)
        ->prefix('teacher')
        ->namespace('Teacher')
        ->group(static function (): void {
            Route::get('/', 'Dashboard')->name('teacher-dashboard');
            Route::get('/student/{student}', 'StudentDetails')
                ->middleware('can:view,student')
                ->name('teacher-student-details');
        });

    Route::middleware(RequiresAdminLevel::class)->group(static function (): void {
        Route::get('/education-programs',
            'EducationProgramsController@index')->name('education-programs'); // Entry to Education programs management
        Route::view('/manage/tips', 'pages.tips.tips-app')->name('tips-app'); // Entry to tips management

        Route::group(['prefix' => '/dashboard'], function (): void {
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

            Route::group(['prefix' => 'api'], function (): void {
                Route::get('chart_details/{label?}',
                    'AnalyticsChartController@getChartDetails')->name('charts-details');
                Route::get('column_values/{table?}/{column?}',
                    'QueryBuilderController@getColumnValues')->name('column-values');
            });

            Route::get('/builder/step/{id}', 'QueryBuilderController@showStep')->name('querybuilder.get');
            Route::post('/builder/step/{id}', 'QueryBuilderController@saveStep')->name('querybuilder.post');
            Route::get('/builder/tables', 'QueryBuilderController@getTables')->name('querybuilder.tables');
            Route::get('/builder/columns/{table?}', 'QueryBuilderController@getColumns')->name('querybuilder.columns');
            Route::get('/builder/relations/{model}',
                'QueryBuilderController@getRelations')->name('querybuilder.relations');
            Route::post('/builder/query', 'QueryBuilderController@executeQuery')->name('querybuilder.query');
            Route::post('/builder/chart', 'QueryBuilderController@getChart')->name('querybuilder.chart');
            Route::post('/builder/testQuery', 'QueryBuilderController@testQuery')->name('querybuilder.test');
        }); // Routes of the dashboard of analytics
        Route::group(['prefix' => 'template'], function (): void {
            Route::get('/', 'TemplateDashboardController@index')->name('template.index');
            Route::get('/view/{id}', 'TemplateDashboardController@show')->name('template.show')->where('id', '[0-9]+');
            Route::post('/{id}', 'TemplateDashboardController@update')->name('template.update')->where('id', '[0-9]+');
            Route::post('/', 'TemplateDashboardController@save')->name('template.save');
            Route::get('/create', 'TemplateDashboardController@create')->name('template.create');
            Route::delete('/{id}', 'TemplateDashboardController@destroy')->name('template.destroy')->where('id',
                '[0-9]+');

            Route::group(['prefix' => 'api'], function (): void {
                // Api routes
                Route::get('tables', 'TemplateDashboardController@getTables')->name('template.tables');
                Route::get('columns/{id?}', 'TemplateDashboardController@getColumns')->name('template.columns');
                Route::get('parameters/{id?}',
                    'TemplateDashboardController@getParameters')->name('template.parameters');
                Route::get('param_html/{id?}', 'TemplateDashboardController@getHTML')->name('template.param-html');
            });
        }); // Routes of the templates used in analytics

        Route::prefix('/api/')->group(static function (): void {
            Route::resource('tip-coupled-statistics', 'TipApi\TipCoupledStatisticController');
            Route::resource('statistics', 'TipApi\StatisticController');
            Route::resource('tips', 'TipApi\TipsController');
            Route::post('moments/create/{tip}', 'TipApi\MomentController@create');
            Route::put('moments/{moment}', 'TipApi\MomentController@update');
            Route::delete('moments/{moment}', 'TipApi\MomentController@delete');
            Route::put('tips/{tip}/cohorts', 'TipApi\TipsController@updateCohorts')->name('tips.updateCohorts');
        }); // Routes of generic API (mostly tips)

        Route::prefix('/education-programs/api')->group(static function (): void {
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
            Route::get('/entity/{type}/{id}/translations', 'EducationProgramsController@entityTranslations');

            Route::put('education-program/{program}', 'EducationProgramsController@updateProgram');
            Route::get('education-program/cohort/{cohort}/competence-description/remove',
                'EducationProgramsController@removeCompetenceDescription');
            Route::post('education-program/cohort/{cohort}/competence-description',
                'EducationProgramsController@createCompetenceDescription');
            Route::get('editable-education-program/{program}', 'EducationProgramsController@getEditableProgram');

            Route::get('education-program/{program}/disable', 'EducationProgramsController@toggleDisabled');
        }); // Routes of education programmes API

        // Dev stuff
        Route::get('/reactlogs', 'ReactLogController@index')->name('reactlogs'); // View React errors
        Route::get('/reactlogs/{reactLog}/fix',
            'ReactLogController@fix')->name('fix-reactlog'); // Remove React error from log
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index'); // Normal logs (exceptions etc)
        Route::get('switch-user/{id}', static function (int $id, CurrentUserResolver $userResolver): RedirectResponse {
            if (!in_array($userResolver->getCurrentUser()->email,
                ['rogier@inesta.com', 'rogier+producing@inesta.com'])) {
                redirect('/');
            }
            Auth::loginUsingId($id);

            return redirect('/');
        }); // Ability to switch to user by ID
        Route::get('/pull-update', static function (): string {
            return shell_exec('git -C /sites/werkplekleren.hu.nl/htdocs fetch && git -C /sites/werkplekleren.hu.nl/htdocs reset --hard origin/master && git -C /sites/werkplekleren.hu.nl/htdocs pull');
        }); // Pulls branch -- often doesn't work. Only necessary due to VPN situation on HU network

        // outside prefix because of namespace issues
        Route::get('admin/home', 'HomeController@showAdminTemplate')->name('home-admin');
        Route::prefix('admin')
            ->namespace('Admin')
            ->group(
                static function (): void {
                    Route::get('/', 'Dashboard')->name('admin-dashboard');
                    Route::match(['GET', 'POST'], '/student/{student}',
                        'StudentDetails')->name('admin-student-details');

                    Route::match(['GET', 'POST'],
                        '/student/{student}/workplacelearningperiod/{workplaceLearningPeriod}/edit',
                        'EditWorkplaceLearningPeriod')->name('admin-student-edit-wplp');

                    Route::get('/student/{student}/delete', 'DeleteStudent')->name('admin-student-delete');
                    Route::get('/student/{student}/workplacelearningperiod/{workplaceLearningPeriod}/delete',
                        'DeleteWorkplaceLearningPeriod')->name('admin-student-delete-wplp');

                    Route::get('/linking', 'Linking')->name('admin-linking');
                    Route::post('/linking/update-workplacelearningperiod', 'UpdateTeacherForWorkplaceLearningPeriod')
                        ->name('update-teacher-for-workplacelearningperiod');

                    Route::post('/linking/update-workplacelearningperiod-csv', 'UpdateTeacherForWorkplaceLearningPeriodCSV@read')
                        ->name('update-teacher-for-workplacelearningperiod-csv');

                    Route::post('/linking/update-workplacelearningperiod-csv-save', 'UpdateTeacherForWorkplaceLearningPeriodCSV@save')
                        ->name('update-teacher-for-workplacelearningperiod-csv-save');
                });
    });

    // Student routes
    Route::get('/saved-learning-items/{category}/{item_id}/create', 'SavedLearningItemController@createItem')->name('saved-learning-item-create');
    Route::post('/activity-export-mail', 'ActivityExportController@exportMail')->middleware('throttle:3,1');
    Route::post('/activity-export-doc', 'ActivityExportController@exportActivitiesToWord');
    Route::get('/download/activity-export-doc/{fileName}',
        'ActivityExportController@downloadWordExport')->name('docx-export-download');

    Route::post('/log', 'LogController@log'); // Logs info of the user's device
    Route::post('/reactlog', 'ReactLogController@store'); // Logs errors occurring in React

    Route::get('/saved-learning-items', 'SavedLearningItemController@index')->name('saved-learning-items');

    Route::middleware('usernotifications')->group(static function (): void {
        // Actions on the profile of a student
        Route::get('profiel', 'ProfileController@show')->name('profile');
        Route::post('profiel/update', 'ProfileController@update');
        Route::put('profiel/change-password', 'ProfileController@changePassword');
        Route::get('canvas-uncouple', 'ProfileController@removeCanvasCoupling')->name('uncouple-canvas');

        Route::get('deadline', 'CalendarController@show')->name('deadline');
        Route::post('deadline/create', 'CalendarController@create')->name('deadline-create');
        Route::post('deadline/update', 'CalendarController@update')->name('deadline-update');

        Route::get('/tip/{tip}/like', 'TipApi\TipsController@likeTip')->name('tips.like');

        Route::middleware(RequireActiveInternship::class)->group(static function (): void {
            Route::post('categorie/update/{id}',
                'ProducingWorkplaceLearningController@updateCategories')->name('categories-update')->where('id',
                '[0-9]*'); // Producing categories
            Route::post('learninggoal/update',
                'ActingWorkplaceLearningController@updateLearningGoals')->name('learninggoals-update'); // Acting learning goals
        });

        Route::middleware('taskTypeRedirect')->group(static function (): void {
            Route::get('/', 'HomeController@showHome')->name('default');
            Route::get('home', 'HomeController@showHome')->name('home');
            Route::get('process', 'ActingActivityController@show')->name('process');
            Route::get('progress', 'ProducingActivityController@progress')->name('progress');
            Route::get('analysis', 'ProducingActivityController@show')->name('analysis');
            Route::get('period/edit/{id}', 'ProducingWorkplaceLearningController@edit')->name('period-edit');
            Route::get('period/create', 'ProducingWorkplaceLearningController@show')->name('period');
        });

        Route::prefix('acting')->group(static function (): void {
            Route::get('home', 'HomeController@showActingTemplate')->name('home-acting');

            Route::post('/user-settings/reflection/save',
                'Acting\StoreReflectionUserSettings')->name('acting-store-reflection-user-settings');

            // Internships & Internship Periods
            Route::get('period/create', 'ActingWorkplaceLearningController@show')
                ->middleware(['can:create,App\Workplace', 'can:create,App\WorkplaceLearningPeriod'])
                ->name('period-acting');

            Route::post('period/create', 'ActingWorkplaceLearningController@create')
                ->middleware(['can:create,App\Workplace', 'can:create,App\WorkplaceLearningPeriod'])
                ->name('period-acting-create');

            Route::get('period/edit/{workplaceLearningPeriod}', 'ActingWorkplaceLearningController@edit')
                ->middleware(['can:update,workplaceLearningPeriod'])
                ->name('period-acting-edit');

            Route::post('period/update/{workplaceLearningPeriod}', 'ActingWorkplaceLearningController@update')
                ->middleware(['can:update,workplaceLearningPeriod'])
                ->name('period-acting-update');

            Route::middleware(RequireActiveInternship::class)->group(static function (): void {
                Route::get('/activities/export', 'Acting\Export\WordExport')->name('acting-activities-word-export');
                Route::post('/activities/export/mail',
                    'Acting\Export\WordMailExport')->name('mail-acting-activities-word-export')->middleware('throttle:3,1');

                Route::get('progress', 'ActingActivityController@progress')->name('progress-acting');
                Route::get('analysis', 'ActingAnalysisController@showChoiceScreen')->name('analysis-acting-choice');
                Route::get('analysis/{year}/{month}', 'ActingAnalysisController@showDetail')
                    ->where([
                        'year'  => '^(20)(\d{2})|all$',
                        'month' => '^([0-1]{1}\d{1})|all$',
                    ])->name('analysis-acting-detail');

                Route::get('evidence/{evidence}/remove', 'EvidenceController@remove')->name('evidence-remove');

                Route::get('reflection/multiple', 'Reflection\DownloadMultiple')
                    ->name('reflection-download-multiple');
                Route::get('reflection/{activityReflection}', 'Reflection\Download')
                    ->middleware('can:view,activityReflection')
                    ->name('reflection-download');
                Route::get('reflection/{activityReflection}/delete', 'Reflection\Delete')
                    ->middleware('can:delete,activityReflection')
                    ->name('reflection-delete');
                Route::get('render-reflection-type/{type}',
                    'Reflection\RenderCreateForm')->name('render-reflection-type');

                Route::get('competence-description/{competenceDescription}',
                    static function (App\CompetenceDescription $competenceDescription) {
                        return response()->download(storage_path('app/'.$competenceDescription->file_name),
                            'competence-description.pdf');
                    })->name('competence-description');

                Route::prefix('process')->group(static function (): void {
                    Route::get('/', 'ActingActivityController@show')
                        ->middleware('can:create,App\LearningActivityActing')
                        ->name('process-acting');

                    Route::post('/create', 'ActingActivityController@create')
                        ->middleware('can:create,App\LearningActivityActing')
                        ->name('process-acting-create');

                    Route::get('/edit/{learningActivityActing}', 'ActingActivityController@edit')
                        ->middleware('can:update,learningActivityActing')
                        ->name('process-acting-edit');

                    Route::post('/update/{learningActivityActing}', 'ActingActivityController@update')
                        ->middleware('can:update,learningActivityActing')
                        ->name('process-acting-update');

                    Route::get('/delete/{learningActivityActing}', 'ActingActivityController@delete')
                        ->middleware('can:delete,learningActivityActing')
                        ->name('process-acting-delete');
                }); // Actions relating to acting activities
            });
        });

        Route::prefix('producing')->group(static function (): void {
            Route::get('home', 'HomeController@showProducingTemplate')->name('home-producing');

            Route::get('period/create', 'ProducingWorkplaceLearningController@show')
                ->middleware(['can:create,App\Workplace', 'can:create,App\WorkplaceLearningPeriod'])
                ->name('period-producing');

            Route::post('period/create', 'ProducingWorkplaceLearningController@create')
                ->middleware(['can:create,App\Workplace', 'can:create,App\WorkplaceLearningPeriod'])
                ->name('period-producing-create');

            Route::get('period/edit/{workplaceLearningPeriod}', 'ProducingWorkplaceLearningController@edit')
                ->middleware(['can:update,workplaceLearningPeriod'])
                ->name('period-producing-edit');

            Route::post('period/update/{workplaceLearningPeriod}', 'ProducingWorkplaceLearningController@update')
                ->middleware(['can:update,workplaceLearningPeriod'])
                ->name('period-producing-update');

            Route::middleware(RequireActiveInternship::class)->group(static function (): void {
                Route::get('progress', 'ProducingActivityController@progress')->name('progress-producing');

                Route::get('report/export',
                    'ProducingReportController@wordExport')->name('report-producing-export');

                Route::get('analysis',
                    'ProducingAnalysisController@showChoiceScreen')->name('analysis-producing-choice');
                Route::get('analysis/{year}/{month}', 'ProducingAnalysisController@showDetail')
                    ->where(['year' => '^(20)(\d{2})|all$', 'month' => '^([0-1]{1}\d{1})|all$'])
                    ->name('analysis-producing-detail');

                Route::get('feedback/{feedback}', 'FeedbackController@show')->name('feedback-producing');
                Route::post('feedback/update/{feedback}',
                    'FeedbackController@update')->name('feedback-producing-update');

                Route::post('/chain/create', 'ChainController@create')->name('chain-create');
                Route::put('/chain/{chain}', 'ChainController@save')->name('chain-save');
                Route::get('/chain/{chain}/delete', 'ChainController@delete')->name('chain-delete');

                Route::prefix('process')->group(static function (): void {
                    Route::get('/', 'ProducingActivityController@show')
                        ->middleware('can:create,App\LearningActivityProducing')
                        ->name('process-producing');

                    Route::post('/create', 'ProducingActivityController@create')
                        ->middleware('can:create,App\LearningActivityProducing')
                        ->name('process-producing-create');

                    Route::get('/edit/{learningActivityProducing}', 'ProducingActivityController@edit')
                        ->middleware('can:update,learningActivityProducing')
                        ->name('process-producing-edit');

                    Route::post('/update/{learningActivityProducing}', 'ProducingActivityController@update')
                        ->middleware('can:update,learningActivityProducing')
                        ->name('process-producing-update');

                    Route::get('/delete/{learningActivityProducing}', 'ProducingActivityController@delete')
                        ->middleware('can:delete,learningActivityProducing')
                        ->name('process-producing-delete');
                }); // Actions relating to producing activities
            });
        });

        Route::get('bugreport', 'HomeController@showBugReport')->name('bugreport');
        Route::post('bugreport/create', 'HomeController@createBugReport')->name('bugreport-create');
    });
});
