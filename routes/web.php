<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\HomeController;
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
    return redirect()->route('login');
});

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::resource('users', AdminUserController::class);
Route::put('/update-fav/{id}', [HomeController::class, 'markAsFavorite'])->middleware(['auth']);

require __DIR__.'/auth.php';
