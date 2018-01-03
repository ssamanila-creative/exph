<?php

// Rate
Route::post('bookings/{booking}/rate', 'Booking\API\Controllers\BookingController@rate')->name('bookings.rate');

//Review
Route::post('bookings/{booking}/review', 'Booking\Controllers\BookingController@review')->name('bookings.review');

// Normal
Route::resource('bookings', 'Booking\Controllers\BookingController')->except(['show']);

Route::delete('bookings/delete/{booking}', 'Booking\Controllers\BookingController@delete')->name('bookings.delete');
Route::get('bookings/trash', 'Booking\Controllers\BookingController@trash')->name('bookings.trash');
Route::post('bookings/{booking}/restore', 'Booking\Controllers\BookingController@restore')->name('bookings.restore');

Route::post('bookings/restore/many', 'Booking\Controllers\BookingManyController@restore')->name('bookings.many.restore');

// Budget
Route::resource('bookings/amenities', 'Booking\Controllers\AmenityController');

Route::get('bookings/budgets', 'Booking\Controllers\BudgetController@index')->name('bookings.budgets.index');
Route::post('bookings/budgets', 'Booking\Controllers\BudgetController@store')->name('bookings.budgets.store');

