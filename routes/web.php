<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CovidController;
use App\Http\Controllers\GoogleOAuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TravelController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
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

Route::get('/travel', [TravelController::class, 'index'])->name('travel.index'); //여행지검색

Route::get('/travel/{id}', [TravelController::class, 'show'])->name('travel.show'); //여행지 세부정보

Route::get('auth/google', [GoogleOAuthController::class, 'redirectToGoogle'])->name('googleLogin'); //구글로그인

Route::get('/google/callback', [GoogleOAuthController::class, 'handleGoogleCallback']); //구글로그인 콜백

Route::get('/review', [ReviewController::class, 'index'])->name('review.index');  //리뷰페이지

Route::get('/review/{id}', [ReviewController::class, 'show'])->name('review.show');  //리뷰 세부정보

Route::get('/getReviews', [ReviewController::class, 'getReviews'])->name('review.getReviews');  //리뷰 가져오기

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/review/create/{id}', [ReviewController::class, 'create'])->name('review.create');  //리뷰 작성 페이지
    Route::post('/review/{id}', [ReviewController::class, 'store'])->name('review.store');  //리뷰 작성
    Route::get('/review/edit/{id}', [ReviewController::class, 'edit'])->name('review.edit'); //리뷰 수정 페이지
    Route::patch('/review/{id}', [ReviewController::class, 'update'])->name('review.update'); //리뷰 수정
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');  //리뷰 삭제
    Route::post('/comment/{id}', [CommentController::class, 'store'])->name('comment.store'); //댓글 작성
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy'); //댓글 삭제
    Route::patch('/comment/{id}', [CommentController::class, 'update'])->name('comment.update'); //댓글 수정
});
