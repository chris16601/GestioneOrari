<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\OrarioController;
use App\Http\Controllers\SendEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Route::group(['middleare' => ['auth', 'checkBanned']], function() {
    //Chart
    Route::get('/home', [ChartController::class, 'index'])->name('home.index');

    //Hour
    Route::get('/hour', [OrarioController::class, 'index'])->name('orario.index');
    Route::get('/get-orario/{month?}/{year?}', [OrarioController::class, 'getOrario'])->name('orario.get');
    Route::post('/save-orario', [OrarioController::class, 'saveOrario'])->name('orario.save');
    Route::get('/delete-hour/{id}', [OrarioController::class, 'deleteOrario'])->name('orario.deleteHour');
    Route::get('edit-hour/{id}', [OrarioController::class, 'editHour'])->name('orario.editHour');
    Route::post('edit-hour-save', [OrarioController::class, 'editHourSave'])->name('orario.edit.save');
    Route::get('/export-orario/{mese}/{anno}', [OrarioController::class, 'exportPdf']);
    Route::get('/export-orario-excel/{mese}/{anno}', [OrarioController::class, 'exportExcel']);

    //Contact Us
    Route::view('/contact-us', 'page.contact');
    Route::get('/invio-email', [SendEmailController::class, 'sendEmail']);
});

