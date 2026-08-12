<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Público
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categorias/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::post('/categorias/{category:slug}/desbloquear', [CategoryController::class, 'unlock'])->name('categories.unlock');

Route::get('/documentos/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

// Admin — autenticação
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('categorias/nova', [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::post('categorias', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('categorias/{category}/editar', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categorias/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categorias/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('documentos/novo', [AdminDocumentController::class, 'create'])->name('documents.create');
        Route::post('documentos', [AdminDocumentController::class, 'store'])->name('documents.store');
        Route::get('documentos/{document}/editar', [AdminDocumentController::class, 'edit'])->name('documents.edit');
        Route::put('documentos/{document}', [AdminDocumentController::class, 'update'])->name('documents.update');
        Route::delete('documentos/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');
    });
});

Route::get('/debug-db', function () {
    try {
        $dbPath = config('database.connections.sqlite.database');
        $exists = file_exists($dbPath);
        $writable = $dbPath ? is_writable(dirname($dbPath)) : false;

        $users = \App\Models\User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'email' => $user->email,
                'password_hash' => substr($user->password, 0, 15) . '...',
            ];
        });

        return response()->json([
            'default_connection' => config('database.default'),
            'sqlite_path' => $dbPath,
            'sqlite_exists' => $exists,
            'sqlite_writable' => $writable,
            'config_admin_email' => config('services.admin.email'),
            'users_in_db' => $users,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});
