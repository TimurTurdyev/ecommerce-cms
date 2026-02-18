<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout']); // Для GET запросов тоже

    // Другие защищенные маршруты
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/example', function () {
        return view('example');
    })->name('example');

    Route::controller(\App\Http\Controllers\UserController::class)
        ->prefix('user')
        ->name('user.')
        ->group(static function (Router $router) {
            $router->get('/', 'index')->name('index');
            $router->get('/create', 'createOrEdit')->name('create');
            $router->get('/{user}/edit', 'createOrEdit')->name('edit');
            $router->delete('/{user}/delete', 'delete')->name('delete');
            $router->post('/store', 'store')->name('store');
            $router->post('/{user}/update', 'update')->name('update');
        });
});
