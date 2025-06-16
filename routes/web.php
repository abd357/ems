<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // this loads home.blade.php from resources/views
})->where('any', '^(?!api).*$');
