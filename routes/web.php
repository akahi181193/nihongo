<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\MatchOldPassword;

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
    //trở về sau khi middleware
    return view('welcome');
    // tạo mối quan hệ giữa các function (check user ? trang home : ve lai trang welcome )
})->middleware('onlyGuest')->name('root');

route::get('/about', function () {
    return view('about');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    // trả về dashboard sau khi dang nhap
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

    Route::get('/editCategory/{id}', [HomeController::class, 'getCategoryById'])->name('getCategoryById');
    Route::post('/editCategory/{id}', [HomeController::class, 'update'])->name('editCategory');
});
