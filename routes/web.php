<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ConnectionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuotationController as AdminQuotationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\QuotationController as ClientQuotationController;
use App\Http\Controllers\Public\CategoryController as PublicCategoryController;
use App\Http\Controllers\Public\ProductController as PublicProductController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [PublicProductController::class, 'index'])->name('public.products.index');
    Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('public.products.show');
    Route::get('/categories', [PublicCategoryController::class, 'index'])->name('public.categories.index');
    Route::get('/categories/{category}', [PublicCategoryController::class, 'show'])->name('public.categories.show');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware(['auth', 'first.time'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/first-time-password', [App\Http\Controllers\Auth\FirstTimePasswordController::class, 'showForm'])->name('first-time-password.form');
    Route::post('/first-time-password', [App\Http\Controllers\Auth\FirstTimePasswordController::class, 'update'])->name('first-time-password.update');

    // Admin routes
    Route::middleware(['role:Super Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class)->except(['show']);
        Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');

        Route::resource('companies', CompanyController::class)->except(['show']);
        Route::post('/companies/{company}/restore', [CompanyController::class, 'restore'])->name('companies.restore');

        Route::resource('products', AdminProductController::class);
        Route::post('/products/{product}/restore', [AdminProductController::class, 'restore'])->name('products.restore');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::post('/categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

        Route::resource('subcategories', SubcategoryController::class)->except(['show']);
        Route::post('/subcategories/{subcategory}/restore', [SubcategoryController::class, 'restore'])->name('subcategories.restore');

        Route::resource('connections', ConnectionController::class)->except(['show']);
        Route::post('/connections/{connection}/restore', [ConnectionController::class, 'restore'])->name('connections.restore');

        Route::get('/quotations', [AdminQuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/{quotation}', [AdminQuotationController::class, 'show'])->name('quotations.show');
        Route::delete('/quotations/{quotation}', [AdminQuotationController::class, 'destroy'])->name('quotations.destroy');
    });

    // Client routes
    Route::middleware(['role:Wholesale Client'])->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

        Route::get('/products', [ClientProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ClientProductController::class, 'show'])->name('products.show');

        Route::get('/quotations', [ClientQuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/create', [ClientQuotationController::class, 'create'])->name('quotations.create');
        Route::post('/quotations', [ClientQuotationController::class, 'store'])->name('quotations.store');
        Route::get('/quotations/{quotation}', [ClientQuotationController::class, 'show'])->name('quotations.show');
        Route::get('/quotations/{quotation}/download', [ClientQuotationController::class, 'download'])->name('quotations.download');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    });
});
