<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaDetailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\UserController;




Route::middleware(['auth', 'kalas'])->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name("dashboard");
    Route::resource('agenda', AgendaController::class)->except(['show','edit','update','destroy']);
    Route::get('agenda/{agenda}/detail/create',[AgendaDetailController::class,'create'])->name('agenda.details.create');
    Route::post('agenda/{agenda}/detail',[AgendaDetailController::class,'store'])->name('agenda.details.store');
    Route::get('agenda/laporan', [AgendaController::class, 'laporan'])->name('agenda.laporan');
    Route::get('agenda/laporan/excel', [AgendaController::class, 'laporanExcel'])->name('agenda.laporan.excel');

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('/admin/laporan/excel', [AdminController::class, 'laporanExcel'])->name('admin.laporan.excel');
    Route::resource('guru', GuruController::class);
    Route::resource('mapel', MapelController::class);
      Route::resource('users', UserController::class);

});

require __DIR__.'/auth.php';
