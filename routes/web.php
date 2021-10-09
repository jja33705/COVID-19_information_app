<?php

use App\Models\Covid;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'localData' => Covid::where('stdDay', Covid::max('stdDay'))->where('gubun', 'not like', '합계')->orderByRaw('localOccCnt + overFlowCnt DESC')->get(),
        'totalData' => Covid::where('gubun', '합계')->orderByDesc('stdDay')->get(),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/myPage', function () {
    return Inertia::render('MyPage');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
});
