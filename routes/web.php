<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicProfileController;


// Главная страница и новости (публичная часть)
Route::get('/', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/category/{slug}', [NewsController::class, 'category'])->name('news.category');

// Стандартный Breeze Dashboard (если понадобится)
Route::get('/dashboard', function () {
    // return view('dashboard');
    return redirect()->route('news.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/news/{post}/comments', [NewsController::class, 'storeComment'])->name('comments.store');

// Профиль пользователя
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Авторизация и регистрация (только для гостей)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    // ... ваши остальные защищенные маршруты ...

    Route::delete('/comments/{comment}', [NewsController::class, 'deleteComment'])->name('comments.destroy');
});

// Публичный профиль пользователя по ID
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
// Публичный просмотр профиля пользователя
Route::get('/user/{user}', [PublicProfileController::class, 'show'])->name('profile.show');

// Выход (только для авторизованных)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

require __DIR__.'/auth.php';