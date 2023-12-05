<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookListController;
use App\Http\Controllers\AcceptRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\DefaultFineController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\BorrowCountController;
use App\Http\Controllers\PdfController;







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






Route::get('/reports', [PdfController::class, 'index'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('reports');
Route::post('/generate-pdf', [PdfController::class, 'generatePdf'])->name('generate-pdf');

Route::post('/generate-pdf-allStudents', [PdfController::class, 'generatePdfForStudents'])->name('generate-pdf-allStudents');

Route::post('/generate-pdf-gradeLevelMostBorrowed', [PdfController::class, 'generatePdfForLevelMostBorrowed'])->name('generate-pdf-gradeLevelMostBorrowed');
Route::post('/generate-pdf-bookCondition', [PdfController::class, 'generatePdfForBookCondition'])->name('generate-pdf-bookCondition');
Route::post('/generate-pdf-mostBorrowedBooks', [PdfController::class, 'generatePdfFormostBorrowedBooks'])->name('generate-pdf-mostBorrowedBooks');

Route::post('/generate-pdf-borrowedByDate', [PdfController::class, 'generatePdfForborrowedByDate'])->name('generate-pdf-borrowedByDate');



// Add a new route for the checkbox-selected PDF export
Route::post('/generate-selected-pdf', [PdfController::class, 'generateSelectedPdf'])->name('generate-selected-pdf');




Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/event', [CalendarController::class, 'events'])->name('event');



Route::post('/borrowCounts', [BookListController::class, 'store'])->name('borrowCounts.store');






Route::get('/startChatStud', [ChatController::class, 'startChatStud'])->name('startChatStud');


Route::get('/startChat/{userId}', [ChatController::class, 'startChat'])->name('startChat');

Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('sendMessage');

Route::delete('/messages/{message}', [ChatController::class, 'delete'])->name('delete_message');




Route::post('/setDefaultFine', [DefaultFineController::class, 'store'])->name('setDefaultFine');
Route::post('/setDailyFine', [DefaultFineController::class, 'storeDaily'])->name('setDailyFine');


Route::get('/navbar', [NavbarController::class, 'index'])->name('navbar');


Route::post('/comments/like/{comment}', [CommentController::class, 'like'])->name('comments.like');



Route::post('/requestBook/{id}', [StudentController::class, 'requestBook'])->name('requestBook');
Route::get('/requests', [StudentController::class, 'requestIndex'])->name('requests');


Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');


Route::post('/replies', [RepliesController::class, 'store'])->name('replies.store');
Route::delete('/replies/{reply}', [RepliesController::class, 'destroy'])->name('replies.destroy');
Route::put('/replies/{reply}', [RepliesController::class, 'update'])->name('replies.update');
Route::get('/replies', [AcceptRequestController::class, 'replies'])->name('replies.replies');;



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
Route::get('/transactions', [AcceptRequestController::class, 'transactions'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('transactions');
Route::get('/history', [AcceptRequestController::class, 'history'])->name('history');
Route::post('/returnBook/{id}', [AcceptRequestController::class , 'returnBook'])->name('returnBook');




Route::delete('/clearNotification/{id}', [AcceptRequestController::class, 'clearNotification'])->name('clearNotification');
Route::delete('/clearReturnedNotification/{id}', [AcceptRequestController::class, 'clearReturnedNotification'])->name('clearReturnedNotification');


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

