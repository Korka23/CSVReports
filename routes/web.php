<?php

use App\Http\Controllers\ReportProcessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/report-processes', [ReportProcessController::class, 'index']);
Route::get('/report-download/{process}', [ReportProcessController::class, 'download'])->name('report.download');
