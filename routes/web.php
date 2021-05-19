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
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/memos', [\App\Http\Controllers\MemoController::class, 'store'])->name('storeMemo');
    Route::get('/memos/edit/{id}', [\App\Http\Controllers\MemoController::class, 'edit'])->name('editMemo');
    Route::patch('/memos/update/{id}', [\App\Http\Controllers\MemoController::class, 'update'])->name('updatmemo');
    Route::get('/memos/delete/{id}', [\App\Http\Controllers\MemoController::class, 'delete'])->name('deleteMemo');
    Route::POST('/category', [\App\Http\Controllers\CateController::class, 'store'])->name('storecategory');
});
