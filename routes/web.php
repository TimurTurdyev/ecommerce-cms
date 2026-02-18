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

    Route::controller(\App\Http\Controllers\CategoryController::class)
        ->prefix('category')
        ->name('category.')
        ->group(static function (Router $router) {
            $router->get('/', 'index')->name('index');
            $router->get('/create', 'createOrEdit')->name('create');
            $router->get('/{category}/edit', 'createOrEdit')->name('edit');
            $router->delete('/{category}/delete', 'delete')->name('delete');
            $router->post('/store', 'store')->name('store');
            $router->post('/{category}/update', 'update')->name('update');
        });

    Route::controller(\App\Http\Controllers\ManufacturerController::class)
        ->prefix('manufacturer')
        ->name('manufacturer.')
        ->group(static function (Router $router) {
            $router->get('/', 'index')->name('index');
            $router->get('/create', 'createOrEdit')->name('create');
            $router->get('/{manufacturer}/edit', 'createOrEdit')->name('edit');
            $router->delete('/{manufacturer}/delete', 'delete')->name('delete');
            $router->post('/store', 'store')->name('store');
            $router->post('/{manufacturer}/update', 'update')->name('update');
        });

    Route::controller(\App\Http\Controllers\ProductController::class)
        ->prefix('product')
        ->name('product.')
        ->group(static function (Router $router) {
            $router->get('/', 'index')->name('index');
            $router->get('/create', 'createOrEdit')->name('create');
            $router->get('/{product}/edit', 'createOrEdit')->name('edit');
            $router->delete('/{product}/delete', 'delete')->name('delete');
            $router->post('/store', 'store')->name('store');
            $router->post('/{product}/update', 'update')->name('update');
        });

    Route::controller(\App\Http\Controllers\OptionController::class)
        ->prefix('option')
        ->name('option.')
        ->group(static function (Router $router) {
            $router->get('/', 'index')->name('index');
            $router->get('/create', 'createOrEdit')->name('create');
            $router->get('/{option}/edit', 'createOrEdit')->name('edit');
            $router->delete('/{option}/delete', 'delete')->name('delete');
            $router->post('/store', 'store')->name('store');
            $router->post('/{option}/update', 'update')->name('update');
            $router->post('/{option}/value', 'storeValue')->name('value.store');
            $router->delete('/value/{optionValue}/delete', 'deleteValue')->name('value.delete');
        });

    Route::controller(\App\Http\Controllers\InformationController::class)
        ->prefix('information')
        ->name('information.')
        ->group(static function (Router $router) {
            $router->get('/', 'index')->name('index');
            $router->get('/create', 'createOrEdit')->name('create');
            $router->get('/{information}/edit', 'createOrEdit')->name('edit');
            $router->delete('/{information}/delete', 'delete')->name('delete');
            $router->post('/store', 'store')->name('store');
            $router->post('/{information}/update', 'update')->name('update');
        });

    Route::controller(\App\Http\Controllers\OrderController::class)
        ->prefix('order')
        ->name('order.')
        ->group(static function (Router $router) {
            $router->get('/', 'index')->name('index');
            $router->get('/{order}/edit', 'edit')->name('edit');
            $router->put('/{order}/update', 'update')->name('update');
        });
});
