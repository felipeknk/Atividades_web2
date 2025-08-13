<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\FineController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Recursos principais
Route::resource('categories', CategoryController::class);
Route::resource('authors', AuthorController::class);
Route::resource('publishers', PublisherController::class);

// Rotas para criação de livros com ID ou Select
Route::get('/books/create-id-number', [BookController::class, 'createWithId'])->name('books.create.id');
Route::post('/books/create-id-number', [BookController::class, 'storeWithId'])->name('books.store.id');

Route::get('/books/create-select', [BookController::class, 'createWithSelect'])->name('books.create.select');
Route::post('/books/create-select', [BookController::class, 'storeWithSelect'])->name('books.store.select');

// Rotas RESTful de livros e usuários
Route::resource('books', BookController::class)->except(['create', 'store']);
Route::resource('users', UserController::class)->except(['create', 'store', 'destroy']);

// Empréstimos
Route::post('/books/{book}/borrow', [BorrowingController::class, 'store'])->name('books.borrow');
Route::get('/users/{user}/borrowings', [BorrowingController::class, 'userBorrowings'])->name('users.borrowings');
Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');

// Multas — apenas bibliotecários
Route::middleware(['auth', 'is_librarian'])->group(function () {
    Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
    Route::post('/fines/{user}/clear', [FineController::class, 'clear'])->name('fines.clear');
});
