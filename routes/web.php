<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConnectionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ManageAdminProductController as AdminProductController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\WholesaleClientUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Client CONTROLLERS
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\RetailerClientUserController as RetailerUserController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\QuotationController as ClientQuotationController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
use App\Http\Controllers\Retailer\ProfileController as RetailerProfileController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Public\CategoryController as PublicCategoryController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ResellerProgramController;
use App\Http\Controllers\Public\ProductController as PublicProductController;
use App\Http\Controllers\Retailer\DashboardController as RetailerDashboardController;
// Retailer Product Controller
use App\Http\Controllers\Retailer\CustomerUserController;
use App\Http\Controllers\Retailer\RetailerProductController;
use App\Http\Controllers\Retailer\CustomerQuotationController;
// Customers Routes
use App\Http\Controllers\Customers\CustomerDashboardController;
use App\Http\Controllers\Customers\CustomerProductController;
use App\Http\Controllers\Customers\ProfileController as CustomerProfileController;



use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
// Public routes (guest + authenticated)
Route::get('/', [HomeController::class, 'index'])->name('public.home.index');
Route::get('/test-pdf', [HomeController::class, 'testPdf'])->name('public.test-pdf');
Route::get('/test-mail', [HomeController::class, 'testEmail'])->name('public.test-mail');

Route::get('/products/{product:slug}', [PublicProductController::class, 'show'])->name('public.products.show');
Route::get('/products/{product:slug}/pdf', [PublicProductController::class, 'downloadPdf'])->name('public.products.pdf');
Route::get('/categories', [PublicCategoryController::class, 'index'])->name('public.categories.index');
Route::get('/categories/{category:slug}', [PublicCategoryController::class, 'show'])->name('public.categories.show');
Route::get('/contact', [ContactController::class, 'index'])->name('public.contact.index');
Route::get('/reseller-program', [ResellerProgramController::class, 'index'])->name('public.reseller-program.index');
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
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // Admin routes
    Route::middleware(['role:Super Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('wholesale-client-users', WholesaleClientUserController::class);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::get('/categories/{category}/subcategories', [CategoryController::class, 'getSubcategories'])->name('categories.fetch-subcategories');
        Route::resource('subcategories', SubcategoryController::class)->except(['show']);
        Route::resource('connections', ConnectionController::class)->except(['show']);
        Route::resource('products', AdminProductController::class);
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [AdminProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
        Route::delete('/profile/avatar', [AdminProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
        Route::get('/setting/genral-setting', [SettingController::class, 'index'])->name('setting.genral-setting');
        Route::put('/setting/genral-setting', [SettingController::class, 'update'])->name('setting.genral-setting.update');
    });

    // Client routes (Wholesale Client)
    Route::middleware(['role:Wholesale Client'])->prefix('client')->name('client.')->group(function () {
        Route::get('/', [ClientDashboardController::class, 'index'])->name('dashboard');
        Route::resource('retailer-users', RetailerUserController::class);
        Route::get('/products', [ClientProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ClientProductController::class, 'show'])->name('products.show');
        // quotations Roures
        Route::resource('quotations', ClientQuotationController::class)->except(['delete']);
        Route::get('/quotations/{quotation}/download', [ClientQuotationController::class, 'download'])->name('quotations.download');
        Route::get('/quotations/{quotation}/preview', [ClientQuotationController::class, 'preview'])->name('quotations.preview');
        Route::post('/quotations/{quotation}/send-email', [ClientQuotationController::class, 'sendEmail'])->name('quotations.send-email');
        Route::patch('/quotations/{quotation}/status', [ClientQuotationController::class, 'updateStatus'])->name('quotations.update-status');
        Route::get('/profile', [ClientProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ClientProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [ClientProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
        Route::delete('/profile/avatar', [ClientProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
        Route::delete('/profile/logo/wholesale', [ClientProfileController::class, 'deleteWholesaleClientLogo'])->name('profile.logo.wholesale.delete');
    });

    // Retailer routes
    Route::middleware(['role:Retailer'])->prefix('retailer')->name('retailer.')->group(function () {
        Route::get('/', [RetailerDashboardController::class, 'index'])->name('dashboard');
        // Manager CUSTOMER Via Retailer
        Route::resource('customer-users', CustomerUserController::class);
        Route::get('/products', [RetailerProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [RetailerProductController::class, 'show'])->name('products.show');
        // QUAOTATION
        Route::resource('quotations', CustomerQuotationController::class);
        Route::controller(CustomerQuotationController::class) ->prefix('quotations') ->name('quotations.')->group(function () {
            Route::get('{quotation}/download', 'download')->name('download');
            Route::get('{quotation}/preview', 'preview')->name('preview');
            Route::post('{quotation}/send-email', 'sendEmail')->name('send-email');
            Route::patch('{quotation}/status', 'updateStatus')->name('update-status');
        });
        Route::get('/profile', [RetailerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [RetailerProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [RetailerProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [RetailerProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
        Route::delete('/profile/avatar', [RetailerProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
        Route::delete('/profile/logo/retailer', [RetailerProfileController::class, 'deleteRetailerClientLogo'])->name('profile.logo.retailer.delete');
    });

    // customer Routes
    Route::middleware(['role:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [CustomerProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
        Route::delete('/profile/avatar', [CustomerProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
        Route::delete('/profile/logo/retailer', [CustomerProfileController::class, 'deleteLogo'])->name('profile.logo.retailer.delete');
        Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [CustomerProductController::class, 'show'])->name('products.show');
    });
});
