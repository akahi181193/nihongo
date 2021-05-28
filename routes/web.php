<?php

use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
})->middleware('onlyGuest')->name('root');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/memos', [\App\Http\Controllers\MemoController::class, 'store'])->name('storeMemo');
    Route::get('/memos/edit/{id}', [\App\Http\Controllers\MemoController::class, 'edit'])->name('editMemo');
    Route::get('/memos/delete/{id}', [\App\Http\Controllers\MemoController::class, 'delete'])->name('deleteMemo');
    Route::post('/category', [\App\Http\Controllers\HomeController::class, 'store'])->name('storecategory');

    // API
    Route::get('/memos/{id}', [\App\Http\Controllers\MemoController::class, 'getMemoById'])->name('getMemoById');
    Route::patch('/memos/{id}', [\App\Http\Controllers\MemoController::class, 'update'])->name('updatememo');
});