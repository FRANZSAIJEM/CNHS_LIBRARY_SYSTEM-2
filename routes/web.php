<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookListController;
use App\Http\Controllers\AcceptRequestController;

use App\Http\Controllers\Controller;


use App\Http\Middleware\AdminMiddleware;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'verified', 'account_status'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/student', [StudentController::class, 'index'])->name('student');

});


Route::post('/requestBook/{id}', [StudentController::class, 'requestBook'])->name('requestBook');
Route::get('/requests', [StudentController::class, 'requestIndex'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('requests');




//this will not allow non-admin user to go to student page
Route::get('/student', [StudentController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('student');
Route::get('/book', [BookController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('book');
Route::post('/book', [BookController::class, 'store'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('book');

Route::get('/editBook/{id}', [BookController::class, 'edit'])->name('editBook.edit');
Route::put('/updateBook/{id}', [BookController::class, 'update'])->name('updateBook.update');
Route::get('/viewBook/{id}', [BookController::class, 'viewBook'])->name('viewBook');
Route::delete('/remove-request/{user_id}/{book_id}', [BookController::class, 'removeRequest'])->name('removeRequest');

Route::delete('/acceptedRequests/{id}', [AcceptRequestController::class, 'destroy'])->name('acceptedRequests.destroy');
Route::post('/acceptRequest/{user}/{book}', [AcceptRequestController::class, 'acceptRequest'])->name('acceptRequest');
Route::get('/transactions', [AcceptRequestController::class, 'transactions'])->name('transactions');
Route::get('/history', [AcceptRequestController::class, 'history'])->name('history');

Route::delete('/clearNotification/{id}', [AcceptRequestController::class, 'clearNotification'])->name('clearNotification');

Route::get('/notifications', [AcceptRequestController::class, 'notifications'])->name('notifications');


Route::get('/bookList', [BookListController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('bookList');

Route::delete('/bookList/{id}', [BookListController::class,  'destroy'])->name('bookList.destroy');

//this will make the student toggle enabled or disabled
Route::post('/toggle-account-status/{id}', [StudentController::class, 'toggleAccountStatus'])
    ->middleware(['auth', 'verified'])
    ->name('toggleAccountStatus');

//this will make the student account enabled or disabled
Route::post('/disable-account/{id}', [StudentController::class, 'disableAccount'])
    ->middleware(['auth', 'verified'])
    ->name('disableAccount');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// useless routes
// Just to demo sidebar dropdown links active states.
Route::get('/buttons/text', function () {
    return view('buttons-showcase.text');
})->middleware(['auth'])->name('buttons.text');

Route::get('/buttons/icon', function () {
    return view('buttons-showcase.icon');
})->middleware(['auth'])->name('buttons.icon');

Route::get('/buttons/text-icon', function () {
    return view('buttons-showcase.text-icon');
})->middleware(['auth'])->name('buttons.text-icon');

require __DIR__ . '/auth.php';

