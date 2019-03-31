<?php


Route::post('/login', 'API\RegisterController@login');
Route::post('/register', 'API\RegisterController@register');
Route::middleware('auth:api')->group(function () {
   Route::get('user', 'API\RegisterController@details');
   Route::resource('products', 'API\ProductController');
});