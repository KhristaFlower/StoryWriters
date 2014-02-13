<?php

// TODO : Figure out why sessions are forgotten on every request.

// Homepage
Route::get('/', 'HomeController@homepage');

// About page
Route::get('about', 'HomeController@about');

// Story resources
Route::get('stories/finished', ['as' => 'stories.finished', 'uses' => 'StoryController@storiesFinished']);
Route::get('stories/ongoing', ['as' => 'stories.ongoing', 'uses' => 'StoryController@storiesOngoing']);
Route::get('stories/starting', ['as' => 'stories.starting', 'uses' => 'StoryController@storiesStarting']);
Route::resource('stories', 'StoryController', ['except' => ['edit', 'update', 'destroy']]);

// Member Listing
Route::get('writers', 'WriterController@showAll');

// User profiles
Route::resource('profile', 'ProfileController', ['only' => ['index', 'show']]);

// User accounts resources
Route::get('register', 'AccountController@create');
Route::resource('account', 'AccountController', ['only' => ['create', 'store']]);

// Sessions resources
Route::get('login', ['as' => 'login', 'uses' => 'SessionsController@create']);
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionsController@destroy']);
Route::resource('sessions', 'SessionsController', ['only' => ['create', 'store', 'destroy']]);

// The following routes require an authenticated user to use.
//Route::group(['before' => 'auth'], function()
//{
    // Story routes
    Route::post('stories/{id}/addwriter', ['as' => 'stories.addwriter', 'uses' => 'WriterController@addWriter']);
    Route::get('stories/{id}/acceptinvite', ['as' => 'stories.acceptinvite', 'uses' => 'WriterController@acceptInvite']);
    Route::get('stories/{id}/declineinvite', ['as' => 'stories.declineinvite','uses' => 'WriterController@declineInvite']);
    Route::get('stories/{story_id}/cancelinvite/{user_id}', ['as' => 'stories.cancelinvite', 'uses' => 'WriterController@cancelInvite']);
    Route::get('stories/{id}/leavestory', ['as' => 'stories.leavestory', 'uses' => 'WriterController@leaveStory']);
    Route::get('stories/{id}/beginstory', ['as' => 'stories.beginstory', 'uses' => 'StoryController@beginStory']);
    Route::post('stories/{id}/composesegment', ['as' => 'stories.composesegment', 'uses' => 'StoryController@composeSegment']);
    Route::get('stories/{id}/join/{key}', ['as' => 'stories.join', 'uses' => 'StoryController@joinStory']);
//});

// TODO : Remove below once done with testing ('becomeuser/{id}').
Route::get('becomeuser/{id}', ['as' => 'sessions.becomeuser', 'uses' => 'SessionsController@becomeUser']);
