<?php

use App\Services\googlesheet;
use Illuminate\Support\Facades\Route;

Route::get('/', function (googlesheet $googlesheet) {
    $value = [
        ['3','Pooja','Nursery','5'],
        ['4','Laxmi','Youtube','7']
    ];

    // $googlesheet->saveDataToSheet($value);
    $googlesheet->readGoogleSheet();
    return view('welcome');
});
