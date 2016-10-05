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
// Addition to override the token-based reset form
Route::get('reset/password', 'Auth\PasswordController@showPasswordResetForm');
Route::post('password/reset', 'Auth\PasswordController@reset');

// Register the localization routes (e.g. /nl/rapportage will switch the language to NL)
// Note: The localisation is saved in a session state.
Route::group([
        'before' => 'auth',
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localizationRedirect' ],
        ], function(){
                // Register the Authentication Controller

                /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
                Route::get('/',                                         'HomeController@showHome');
                Route::get('home',                                      'HomeController@showHome');

                // User Creation and modification
                Route::get('profiel',                                   'ProfileController@show');
                Route::post('profiel/update',                           'ProfileController@update');

                // Internships & Internship Periods
                Route::get('stageperiode/edit/{id}',                    'InternshipController@editInternshipPeriod');
                Route::post('stageperiode/update/{id}',                 'InternshipController@updateInternshipPeriod');

                // Category and SWV creation/updating
                Route::post('categorie/update/{id}',                    'InternshipController@updateCategories');
                Route::post('samenwerkingsverband/update/{id}',         'InternshipController@updateCooperations');

                // Calendar Creation
                Route::get('deadline',                                  'CalendarController@show');
                Route::post('deadline/create',                          'CalendarController@create');
                Route::post('deadline/update',                          'CalendarController@update');

                // Bugreport
                Route::get('bugreport',                                 'HomeController@showBugReport');
                Route::post('bugreport/create',                         'HomeController@createBugReport');

                // Task Creation
                Route::get('leerproces',                                'TaskController@show');
                Route::post('leerproces/create',                        'TaskController@create');
                Route::post('leerproces/update/{id}',                   'TaskController@update');
                // Feedback
                Route::get('feedback/{id}',                             'TaskController@feedback');
                Route::post('feedback/update/{id}',                     'TaskController@updateFeedback');
                // Progress
                Route::get('voortgang/{page}',                          'TaskController@progress');
                Route::get('weekstaten/export',                         'ReportController@export');

                // Report Creation
                Route::get('analyse',                                   'AnalysisController@showChoiceScreen');
                Route::get('analyse/{year}/{month}',                   'AnalysisController@showDetail');

                // Chart Generation
                Route::get('chart/generate/{id}',                       'ChartController@show');

                // Catch Other routes and redirect to home
                Route::get('/{route}',                                  'HomeController@showDefault');
        });