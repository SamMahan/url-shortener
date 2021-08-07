<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
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

Route::get('/', [UrlController::class, 'redirect']);

Route::post('/url_hash', [UrlController::class, 'save']);

Route::get('/url_hash/popular', [UrlController::class, 'getSorted']);

// Route::post('/url_hash',);
