<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\NewWorksheet;
use App\Http\Controllers\WorksheetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});*/

Route::get('/', [NewWorksheet::class, 'index']);

Route::post('/store-worksheet', [NewWorksheet::class, 'store']);

// Munkalapok kezelése
Route::get('/opened-works', [WorksheetController::class, 'opened'])->name('worksheets.opened');
Route::get('/closed-works', [WorksheetController::class, 'closed'])->name('worksheets.closed');
Route::post('/close-worksheet/{id}', [WorksheetController::class, 'close'])->name('worksheets.close');
Route::post('/reopen-worksheet/{id}', [WorksheetController::class, 'reopen'])->name('worksheets.reopen');
Route::put('/update-worksheet/{id}', [WorksheetController::class, 'update'])->name('worksheets.update');
Route::delete('/delete-worksheet/{id}', [WorksheetController::class, 'destroy'])->name('worksheets.destroy');
Route::get('/download-worksheet/{id}', [WorksheetController::class, 'downloadPdf'])->name('worksheets.download');

// Adatok kezelése (szolgáltatások, anyagok)
Route::get('/data-upload', [DataController::class, 'index'])->name('data.index');
Route::post('/data/services', [DataController::class, 'storeService'])->name('data.services.store');
Route::put('/data/services/{id}', [DataController::class, 'updateService'])->name('data.services.update');
Route::delete('/data/services/{id}', [DataController::class, 'destroyService'])->name('data.services.destroy');
Route::post('/data/materials', [DataController::class, 'storeMaterial'])->name('data.materials.store');
Route::put('/data/materials/{id}', [DataController::class, 'updateMaterial'])->name('data.materials.update');
Route::delete('/data/materials/{id}', [DataController::class, 'destroyMaterial'])->name('data.materials.destroy');