<?php

use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendedorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogoController::class, 'index'])->name('catalogo');
Route::get('/vendedor/{slug}', [CatalogoController::class, 'vendedor'])->name('vendedor');
Route::middleware('guest')->group(function () {
	Route::get('/ingresar', [AuthController::class, 'showLogin'])->name('login');
	Route::post('/ingresar', [AuthController::class, 'login'])->name('login.store');
	Route::get('/registrarse', [AuthController::class, 'showRegister'])->name('register');
	Route::post('/registrarse', [AuthController::class, 'register'])->name('register.store');
});
Route::post('/salir', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->prefix('panel')->name('panel.')->group(function () {
	Route::get('/', [VendedorController::class, 'dashboard'])->name('dashboard');
	Route::get('/vehiculos/crear', [VendedorController::class, 'create'])->name('vehiculos.create');
	Route::post('/vehiculos', [VendedorController::class, 'store'])->name('vehiculos.store');
	Route::delete('/vehiculos/{vehiculo}', [VendedorController::class, 'destroy'])->name('vehiculos.destroy');
});
Route::prefix('admin')->name('admin.')->group(function () {
	Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');
	Route::get('/{resource}', [AdminController::class, 'index'])->name('index');
	Route::get('/{resource}/crear', [AdminController::class, 'create'])->name('create');
	Route::post('/{resource}', [AdminController::class, 'store'])->name('store');
	Route::get('/{resource}/{id}/editar', [AdminController::class, 'edit'])->name('edit');
	Route::put('/{resource}/{id}', [AdminController::class, 'update'])->name('update');
	Route::delete('/{resource}/{id}', [AdminController::class, 'destroy'])->name('destroy');
});
