<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PerfilController;

Route::get('/perfil', [PerfilController::class, 'index']);
