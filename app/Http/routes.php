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

// Generic page routes
Route::get('/', ['as' => 'home', 'uses' => 'PagesController@getHome']);
Route::get('about', ['as' => 'about', 'uses' => 'PagesController@getAbout']);

// Account routes group
Route::group(['prefix' => 'account'], function() {

    // Login routes
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
    Route::post('login', 'Auth\AuthController@postLogin');

    // Logout route
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

    // Registration routes
    Route::get('register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
    Route::post('register', 'Auth\AuthController@postRegister');

});

/*
 * The below still needs doing and the routes there are temporary and used to make the views work.
 */

Route::get('stories', ['as' => 'stories', 'uses' => 'PagesController@getAbout']);
Route::get('writers', ['as' => 'writers', 'uses' => 'PagesController@getAbout']);
//Route::get('auth/logout', ['as' => 'logout', 'uses' => 'PagesController@getAbout']);
//Route::get('auth/login', ['as' => 'login', 'uses' => 'PagesController@getAbout']);
//Route::get('auth/register', ['as' => 'register', 'uses' => 'PagesController@getAbout']);
