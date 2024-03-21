<?php

use App\Http\Controllers\FeedbackController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', \App\Http\Controllers\IndexController::class)->name('index');
Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin', [FeedbackController::class, 'index'])->name('admin');
    Route::delete('/feedback', [FeedbackController::class, 'delete'])->name('feedback.delete');
    Route::patch('/feedback', [FeedbackController::class, 'restore'])->name('feedback.restore');
});
