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

                // Bugreport
                Route::get('bugreport',                                 'HomeController@showBugReport')->name('bugreport');
                Route::post('bugreport/create',                         'HomeController@createBugReport');
        }
);

Route::group([
            'before' => 'auth',
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => [ 'localizationRedirect', 'taskTypeRedirect' ],
            ], function(){

                Route::get('leerproces',                               'ProducingActivityController@show')->name('leerproces');
                // Producing activity
                Route::get('producing',                                'ProducingActivityController@show')->name('leerproces-producing');
                Route::post('producing/create',                        'ProducingActivityController@create');
                Route::post('producing/update/{id}',                   'ProducingActivityController@update');

                // acting activty
                Route::get('acting',                                    'ActingActivityController@show');
                Route::post('acting/create',                            'ActingActivityController@create');

                // Progress
                Route::get('voortgang/{page}',                          'ProducingActivityController@progress');
                Route::get('weekstaten/export',                         'ReportController@export');

                // Report Creation
                Route::get('analyse',                                   'ProducingAnalysisController@showChoiceScreen')->name('analyse-redirect');
                Route::get('analyse-producing',                         'ProducingAnalysisController@showChoiceScreen')->name('analyse-producing-choice');
                Route::get('analyse-producing/{year}/{month}',          'ProducingAnalysisController@showDetail')->name('analyse-producing-detail');

                // Feedback
                Route::get('feedback/{id}',                             'ProducingActivityController@feedback')->where('id', '[0-9]*');
                Route::post('feedback/update/{id}',                     'ProducingActivityController@updateFeedback');
            }
);
