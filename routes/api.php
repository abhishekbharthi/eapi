<?php

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;


Route::apiResource('/products', 'ProductController');

Route::group(['prefix' => 'products'], function(){
    Route::apiResource('{product}/reviews', 'ReviewController');
});

