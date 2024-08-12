<?php

use App\Http\Controllers\RiwayatDsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
	DashboardController,
	DiagnosaController,
	RiwayatController,
	PenyakitController,
	GejalaController,
	RuleController,
	UserController
};
use App\Http\Controllers\DataBasisDsController;
use App\Http\Controllers\DiagnosaDsController;

Route::redirect('/', '/login');

Route::group([
	'middleware' => 'auth',
	'prefix' => 'panel',
	'as' => 'admin.'
], function(){
	// diagnosa menu
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::get('/diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa');
	Route::post('/diagnosa', [DiagnosaController::class, 'diagnosa'])->name('diagnosa');

	// diagnosa DS
	Route::get('/diagnosaDs', [DiagnosaDsController::class, 'index'])->name('diagnosaDs');
    Route::post('/diagnosaDs', [DiagnosaDsController::class, 'kalkulator'])->name('diagnosaDs');

	// logs
	Route::get('/logs', [DashboardController::class, 'activity_logs'])->name('logs');
	Route::post('/logs/delete', [DashboardController::class, 'delete_logs'])->name('logs.delete');

	// menu riwayat
	Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.daftar');
	Route::get('/riwayat/detail/{riwayat}', [RiwayatController::class, 'show'])->name('riwayat');
	Route::get('/riwayat/dempster-shafer/detail/{riwayat}', [RiwayatController::class, 'showCalculateDs'])->name('showCalculateDs');

	//menu riwayat ds
	Route::get('/riwayatds', [RiwayatDsController::class, 'index'])->name('riwayatds.daftar');
    Route::get('data-riwayatds/{id_diagnosa}', [RiwayatDsController::class, 'showdata'])->name('riwayatds.show');
    Route::delete('data-riwayatds/{id_diagnosa}', [RiwayatDsController::class, 'destroy'])->name('riwayatds.delete');;

	// Member menu
	Route::get('/member', [UserController::class, 'index'])->name('member');
	Route::get('/member/create', [UserController::class, 'create'])->name('member.create');
	Route::post('/member/create', [UserController::class, 'store'])->name('member.create');
	Route::get('/member/{id}/edit', [UserController::class, 'edit'])->name('member.edit');
	Route::post('/member/{id}/update', [UserController::class, 'update'])->name('member.update');
	Route::post('/member/{id}/delete', [UserController::class, 'destroy'])->name('member.delete');

	// menu penyakit
	Route::get('/penyakit', [PenyakitController::class, 'index'])->name('penyakit');
	Route::post('/penyakit', [PenyakitController::class, 'store'])->name('penyakit.store');
	Route::get('/penyakit/json', [PenyakitController::class, 'json'])->name('penyakit.json');
	Route::post('/penyakit/update', [PenyakitController::class, 'update'])->name('penyakit.update');
	Route::post('/penyakit/{penyakit}/destroy', [PenyakitController::class, 'destroy'])->name('penyakit.destroy');

	// menu gejala
	Route::get('/gejala', [GejalaController::class, 'index'])->name('gejala');
	Route::post('/gejala', [GejalaController::class, 'store'])->name('gejala.store');
	Route::get('/gejala/json', [GejalaController::class, 'json'])->name('gejala.json');
	Route::post('/gejala/update', [GejalaController::class, 'update'])->name('gejala.update');
	Route::post('/gejala/{gejala}/destroy', [GejalaController::class, 'destroy'])->name('gejala.destroy');

	// menu rules
	Route::get('/rules/{id}', [RuleController::class, 'index'])->name('rules');
	Route::post('/rules/{id}/update', [RuleController::class, 'update'])->name('rules.update');

	// menu rules ds
	Route::get('/ruleDs', [DataBasisDsController::class,'index'])->name('ruleDs');
	Route::post('/ruleDs', [DataBasisDsController::class, 'store'])->name('ruleDs.store');
	Route::get('/ruleDs/json', [DataBasisDsController::class, 'json'])->name('ruleDs.json');
	Route::post('/ruleDs/update', [DataBasisDsController::class, 'update'])->name('ruleDs.update');
	Route::post('/ruleDs/{ruleDs}/destroy', [DataBasisDsController::class, 'destroy'])->name('ruleDs.destroy');


	// Profile menu
	Route::view('/profile', 'admin.profile')->name('profile');
	Route::post('/profile', [DashboardController::class, 'profile_update'])->name('profile');
	Route::post('/profile/upload', [DashboardController::class, 'upload_avatar'])
		->name('profile.upload');

	Route::get('/tes', function() {
	})->name('test');

});


require __DIR__.'/auth.php';
