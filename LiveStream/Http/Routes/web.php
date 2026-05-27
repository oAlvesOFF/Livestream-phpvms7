<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'live'], function () {
    // Public Passenger Panel
    Route::get('/passenger/{pirep_id}', 'Frontend\PassengerPanelController@show')
        ->name('livestream.passenger.show');

    // AJAX Passenger Interaction
    Route::post('/passenger/{pirep_id}/interact', 'Frontend\PassengerPanelController@interact')
        ->name('livestream.passenger.interact');

    // OBS Overlay (transparent, no auth needed)
    Route::get('/overlay/{pirep_id}', 'Frontend\OverlayController@show')
        ->name('livestream.overlay.show');
});

// Protected routes for pilots
Route::group(['prefix' => 'live/profile', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', 'Frontend\ProfileController@index')
        ->name('livestream.profile.index');
    Route::post('/', 'Frontend\ProfileController@store')
        ->name('livestream.profile.store');
});
