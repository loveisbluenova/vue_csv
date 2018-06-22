<?php

Route::get('api/customers', 'CustomerController@index');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('/payments', 'PaymentController');	
Route::get('/payments/mapfields', 'PaymentController@getfields');	