<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\TrashController;

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

route::get('/about', function () {
    return view('about');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/category', [HomeController::class, 'store'])->name('storecategory');
    Route::post('/memos', [MemoController::class, 'store'])->name('storeMemo');
    Route::get('/memos/edit/{id}', [MemoController::class, 'edit'])->name('editMemo');
    Route::get('/memos/delete/{id}', [MemoController::class, 'delete'])->name('deleteMemo');


    Route::get('/trashes', [TrashController::class, 'index'])->name('trashes');
    Route::get('/trashes/{id}/restore', [TrashController::class, 'restore'])->name('restore-memo');
    Route::get('/trashes/{id}/force-delete', [TrashController::class, 'destroy'])->name('force-delete-memo');

    // API
    Route::get('/memos/{id}', [MemoController::class, 'getMemoById'])->name('getMemoById');
    Route::patch('/memos/{id}', [MemoController::class, 'update'])->name('updatememo');
});
