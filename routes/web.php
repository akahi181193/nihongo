<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\GithubController;

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


//Language
Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::get('/', function () {
    //trở về sau khi middleware
    return view('welcome');
    // tạo mối quan hệ giữa các function (check user ? trang home : ve lai trang welcome )
})->middleware('onlyGuest')->name('root');


route::get('/about', function () {
    return view('about');
});

Auth::routes();

// -----Link to Google Account------
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// -----Link to Facebook Account------
Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

// -----Link to GitHub Account------
Route::get('auth/github', [GithubController::class, 'redirectToGithub']);
Route::get('auth/github/callback', [GithubController::class, 'handleGithubCallback']);

// -----Link to User Account------
Route::group(['middleware' => ['auth']], function () {
    
    // // -----After confirm login back to home------
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    

    Route::get('/profile/{id}', [UserProfileController::class, 'store'])->name('profile');
    Route::post('/updateprofile/{id}', [UserProfileController::class, 'update'])->name('updateprofile');
    Route::get('/deleteprofile/{id}', [UserProfileController::class, 'destroy'])->name('deleteprofile');


    Route::get('/resetpass/{id}', [UserProfileController::class, 'reset'])->name('resetpass');
    Route::post('/updatepass/{id}', [UserProfileController::class, 'updatepass'])->name('updatepass');
    
    // dùng post truyền dữ liệu theo mảng dạng form 
    Route::post('/category', [HomeController::class, 'store'])->name('storecategory');
 
    Route::post('/memos', [MemoController::class, 'store'])->name('storeMemo');

    // phải truyền tham số id vào {} để web hiểu đường dẫn cho phần edit , delete
    // Route::get('/memos/edit/{id}', [MemoController::class, 'edit'])->name('editMemo');
    Route::get('/memos/delete/{id}', [MemoController::class, 'delete'])->name('delete-memo');
    Route::get('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('categoryDelete');


    Route::get('/trashes', [TrashController::class, 'index'])->name('trashes');
    Route::get('/trashes/{id}/restore-memo', [TrashController::class, 'restoreMemo'])->name('restore-memo');
    Route::get('/trashes/{id}/force-delete-memo', [TrashController::class, 'destroyMemo'])->name('force-delete-memo');

    Route::get('/trashes/{id}/restore-category', [TrashController::class, 'restoreCategory'])->name('restore-category');
    Route::get('/trashes/{id}/force-delete-category', [TrashController::class, 'destroyCategory'])->name('force-delete-category');


    // API
    Route::get('/memos/{id}', [MemoController::class, 'getMemoById'])->name('getMemoById');
    Route::post('/memos/{id}', [MemoController::class, 'update'])->name('updatememo');

    Route::get('/categories/{id}', [HomeController::class, 'getCategoryById'])->name('getCategoryById');
    Route::post('/categories/{id}', [HomeController::class, 'update'])->name('editCategory');
});
