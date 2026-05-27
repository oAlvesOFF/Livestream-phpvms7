<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'live'], function () {
    // Public Passenger Panel
    Route::get('/passenger/{pirep_id}', 'PassengerPanelController@show')
        ->name('passenger.show');

    // AJAX Passenger Interaction
    Route::post('/passenger/{pirep_id}/interact', 'PassengerPanelController@interact')
        ->name('passenger.interact');

    // OBS Overlay (transparent, no auth needed)
    Route::get('/overlay/{pirep_id}', 'OverlayController@show')
        ->name('overlay.show');
});

// Protected routes for pilots
Route::group(['prefix' => 'live/profile', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', 'ProfileController@index')
        ->name('profile.index');
    Route::post('/', 'ProfileController@store')
        ->name('profile.store');
});
