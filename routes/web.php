<?php

use App\Http\Controllers\CovidController;
use App\Http\Controllers\GoogleOAuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TravelController;
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

Route::get('/', [CovidController::class, 'index'])->name('covid.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/travel', [TravelController::class, 'index'])->name('travel.index');

Route::get('/travel/{id}', [TravelController::class, 'show'])->name('travel.show');

Route::get('auth/google', [GoogleOAuthController::class, 'redirectToGoogle'])->name('googleLogin');

Route::get('/google/callback', [GoogleOAuthController::class, 'handleGoogleCallback']);

Route::get('/review', [ReviewController::class, 'index'])->name('review.index');

Route::get('/getReviews', [ReviewController::class, 'getReviews'])->name('review.getReviews');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/review/create', [ReviewController::class, 'create'])->name('review.create');

    Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');
});
