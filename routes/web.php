<?php

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
    return redirect('/login');
});
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function (){

    /**
     * dashboard routes
     */
    Route::get('/dashboard',[\App\Http\Controllers\Frontend\DashboardController::class,'show'])->name('dashboard');
    Route::get('/system-log', [\App\Http\Controllers\Frontend\DashboardController::class,'viewLog'])->name('log');
    Route::get('api/system-log', [\App\Http\Controllers\Frontend\DashboardController::class,'getLogs']);

    /**
     * user routes
     */
    Route::post('/user/generate-token',[\App\Http\Controllers\Backend\UserController::class,'generateApiToken']);
    Route::resource('user', \App\Http\Controllers\Backend\UserController::class);

    /**
     * Settings routes
     */
    Route::post('settings/update',[\App\Http\Controllers\Backend\SettingController::class,'updateSettings']);


});



