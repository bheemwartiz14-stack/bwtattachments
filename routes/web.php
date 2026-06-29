<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConnectionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ManageAdminProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductWholesalePriceController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WholesaleClientUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\QuotationController as AdminQuotationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\RetailerClientUserController as RetailerUserController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\QuotationController as ClientQuotationController;
use App\Http\Controllers\Public\CategoryController as PublicCategoryController;
use App\Http\Controllers\Public\ProductController as PublicProductController;
use App\Http\Controllers\Retailer\DashboardController as RetailerDashboardController;
use Illuminate\Support\Facades\Route;

// Public routes (guest + authenticated)
Route::get('/', [PublicProductController::class, 'index'])->name('public.products.index');
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('public.products.show');
Route::get('/categories', [PublicCategoryController::class, 'index'])->name('public.categories.index');
Route::get('/categories/{category}', [PublicCategoryController::class, 'show'])->name('public.categories.show');

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::post('/upload-temp', [FileController::class, 'store'])->name('upload-temp')->middleware('auth');
Route::delete('/media/{media}', [FileController::class, 'destroy'])->name('media.destroy')->middleware('auth');
Route::middleware(['auth', 'first.time'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/first-time-password', [App\Http\Controllers\Auth\FirstTimePasswordController::class, 'showForm'])->name('first-time-password.form');
    Route::post('/first-time-password', [App\Http\Controllers\Auth\FirstTimePasswordController::class, 'update'])->name('first-time-password.update');

    // Admin routes
    Route::middleware(['role:Super Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('wholesale-client-users', WholesaleClientUserController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::get('/categories/{category}/subcategories', [CategoryController::class, 'getSubcategories'])->name('categories.fetch-subcategories');
        Route::resource('subcategories', SubcategoryController::class)->except(['show']);
        Route::resource('connections', ConnectionController::class)->except(['show']);
        Route::resource('products', AdminProductController::class);
        Route::get('/product-pricing/preview/product/{id}', [ProductWholesalePriceController::class, 'getProductPreview'])->name('product-pricing.preview.product');
        Route::get('/product-pricing/preview/user/{id}', [ProductWholesalePriceController::class, 'getUserPreview'])->name('product-pricing.preview.user');
        Route::resource('product-pricing', ProductWholesalePriceController::class);
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'changePassword'])->name('profile.password');
    });

    // Client routes (Wholesale Client)
    Route::middleware(['role:Wholesale Client'])->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('retailer-users', RetailerUserController::class)->except(['show']);
        Route::get('/products', [ClientProductController::class, 'index'])->name('products.index');
        Route::get('/products/search', [ClientProductController::class, 'search'])->name('products.search');
        Route::get('/products/{product}', [ClientProductController::class, 'show'])->name('products.show');
        Route::patch('/products/{product}/margin', [App\Http\Controllers\Client\ProductPriceManageController::class, 'updateMargin'])->name('products.margin');
        Route::get('/quotations', [ClientQuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/create', [ClientQuotationController::class, 'create'])->name('quotations.create');
        Route::post('/quotations', [ClientQuotationController::class, 'store'])->name('quotations.store');
        Route::get('/quotations/{quotation}', [ClientQuotationController::class, 'show'])->name('quotations.show');
        Route::get('/quotations/{quotation}/download', [ClientQuotationController::class, 'download'])->name('quotations.download');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    });

    // Retailer routes
    Route::middleware(['role:Retailer'])->prefix('retailer')->name('retailer.')->group(function () {
        Route::get('/dashboard', [RetailerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/products', [ClientProductController::class, 'index'])->name('products.index');
        Route::get('/products/search', [ClientProductController::class, 'search'])->name('products.search');
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
