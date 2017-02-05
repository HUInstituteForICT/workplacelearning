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


Route::auth();

// Register the localization routes (e.g. /nl/rapportage will switch the language to NL)
// Note: The localisation is saved in a session state.
Route::group([
        'before' => 'auth',
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localizationRedirect' ],
        ], function(){
                // Register the Authentication Controller

                // Catch the stat registration post
                Route::post('/log',                                     'LogController@log');

                /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
                Route::get('/',                                         'HomeController@showHome');
                Route::get('home',                                      'HomeController@showHome')->name('home');

                // User Creation and modification
                Route::get('profiel',                                   'ProfileController@show');
                Route::post('profiel/update',                           'ProfileController@update');

                // Internships & Internship Periods
                Route::get('stageperiode/edit/{id}',                    'WorkplaceLearningController@edit')->where('id', '[0-9]*');
                Route::get('stageperiode/create',                       'WorkplaceLearningController@show')->name('workplacelearningperiod');
                Route::post('stageperiode/create',                      'WorkplaceLearningController@create');
                Route::post('stageperiode/update/{id}',                 'WorkplaceLearningController@update')->where('id', '[0-9]*');

                // Category updating
                Route::post('categorie/update/{id}',                    'WorkplaceLearningController@updateCategories')->where('id', '[0-9]*');

                // Calendar Creation
                Route::get('deadline',                                  'CalendarController@show')->name('deadline');
                Route::post('deadline/create',                          'CalendarController@create')->name('deadline-create');
                Route::post('deadline/update',                          'CalendarController@update')->name('deadline-update');

                // acting activty
                Route::get('acting',                                    'ActingActivityController@show')->name('leerproces-acting');
                Route::post('acting/create',                            'ActingActivityController@create')->name('leerproces-acting-create');

                // Bugreport
                Route::get('bugreport',                                 'HomeController@showBugReport')->name('bugreport');
                Route::post('bugreport/create',                         'HomeController@createBugReport');


                Route::group([
                                'middleware' => [ 'taskTypeRedirect' ],
                            ], function(){
                                Route::get('process',   'ActingActivityController@show')->name('process');
                                Route::get('progress',  'ProducingActivityController@progress')->name('progress');
                                Route::get('analysis',  'ProducingActivityController@show')->name('analysis');
                            }
                );

                /* EP Type: Acting */
                Route::group([
                                'prefix' => "/acting",
                            ], function(){
                                Route::get('process',                           'ActingActivityController@show')->name('process-acting');
                                Route::post('process/create',                   'ActingActivityController@create')->name('process-acting-create');
                                Route::post('process/update/{id}',              'ActingActivityController@update')->name('process-acting-update');

                                Route::get('progress/{page}',                   'ActingActivityController@show')->where('page', '[1-9]{1}[0-9]*')->name('progress');

                                /*
                                * Disabled for now, analysis for acting is status: TODO
                                */

                                // Progress
                                //Route::get('progress/{page}',                   'ActingActivityController@progress')->where('page', '[1-9]{1}[0-9]*')->name('progress');
                                //Route::get('report/export',                     'ReportController@export')->name('report-producing-export');

                                // Report Creation
                                Route::get('analysis',                          'ProducingAnalysisController@showChoiceScreen')->name('analysis-acting-choice');
                                Route::get('analysis/{year}/{month}',           'ProducingAnalysisController@showDetail')->name('analysis-acting-detail');
                            }
                );

                /* EP Type: Producing */
                Route::group([
                                'prefix' => "/producing",
                            ], function(){
                                Route::get('process',                           'ProducingActivityController@show')->name('process-producing');
                                Route::post('process/create',                   'ProducingActivityController@create')->name('process-producing-create');
                                Route::post('process/update/{id}',              'ProducingActivityController@update')->name('process-producing-update');

                                // Progress
                                Route::get('progress/{page}',                   'ProducingActivityController@progress')->where('page', '[1-9]{1}[0-9]*')->name('progress-producing');
                                Route::get('report/export',                     'ProducingReportController@export')->name('report-producing-export');

                                // Report Creation
                                Route::get('analysis',                          'ProducingAnalysisController@showChoiceScreen')->name('analysis-producing-choice');
                                Route::get('analysis/{year}/{month}',           'ProducingAnalysisController@showDetail')->name('analysis-producing-detail');

                                // Feedback
                                Route::get('feedback/{id}',                     'ProducingActivityController@feedback')->where('id', '[0-9]*')->name('feedback-producing');
                                Route::post('feedback/update/{id}',             'ProducingActivityController@updateFeedback')->name('feedback-producing-update');
                            }
                );

        }
);




