<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('intro');
});
Route::get('/explore', function () {
    return view('explore');
});
