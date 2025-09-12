<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', function (Request $request) {
    return view('welcome');
});
