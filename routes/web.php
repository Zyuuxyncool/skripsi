<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForecastController;

Route::get('/', [ForecastController::class, 'index'])->name('forecast.index');
Route::post('/import', [ForecastController::class, 'import'])->name('forecast.import');
Route::get('/export', [ForecastController::class, 'export'])->name('forecast.export');

