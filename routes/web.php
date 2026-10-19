<?php
use App\Http\Controllers\SiteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\ContentController as AdminContent;
use App\Http\Controllers\Admin\PageController as AdminPages;
use Illuminate\Support\Facades\Route;
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/modeles', [SiteController::class, 'index'])->name('cars.index');
Route::get('/modeles/{car}', [SiteController::class, 'show'])->name('cars.show');
Route::get('/demande-essai', [SiteController::class, 'contact'])->name('contact');
Route::post('/demande-essai', [SiteController::class, 'store'])->name('contact.store');

// Pages du header (KpHeader)
Route::get('/showroom', [SiteController::class, 'index'])->name('showroom');
Route::get('/essai', [SiteController::class, 'contact'])->name('essai');
Route::get('/opportunities', [PageController::class, 'show'])->defaults('slug', 'opportunities')->name('opportunities');
Route::get('/sav', [PageController::class, 'show'])->defaults('slug', 'sav')->name('sav');
Route::get('/contact', [PageController::class, 'show'])->defaults('slug', 'contact')->name('contact.page');
Route::get('/kpandji-automobiles', [PageController::class, 'show'])->defaults('slug', 'kpandji-automobiles')->name('automobiles');
Route::get('/ecologie', [PageController::class, 'show'])->defaults('slug', 'ecologie')->name('ecologie');
Route::get('/emplois', [PageController::class, 'show'])->defaults('slug', 'emplois')->name('emplois');

// ===== Administration =====
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\CarController as AdminCars;
use App\Http\Controllers\Admin\DashboardController as AdminDash;
Route::get('/admin/login', [AdminAuth::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminAuth::class, 'login'])->middleware('throttle:10,1')->name('admin.login');
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [AdminAuth::class, 'logout'])->name('logout');
    Route::get('/', [AdminDash::class, 'index'])->name('dashboard');
    Route::get('/promotion', [AdminDash::class, 'promo'])->name('promo');
    Route::put('/promotion', [AdminDash::class, 'savePromo'])->name('promo.save');
    Route::get('/demandes', [AdminDash::class, 'requests'])->name('requests');
    Route::patch('/demandes/{item}', [AdminDash::class, 'toggleHandled'])->name('requests.toggle');
    Route::post('/voitures/ordre', [AdminCars::class, 'reorder'])->name('cars.reorder');
    Route::patch('/voitures/{car:id}/visibilite', [AdminCars::class, 'toggle'])->name('cars.toggle');
    Route::get('/contenu', [AdminContent::class, 'index'])->name('content');
    Route::put('/contenu/{tab}', [AdminContent::class, 'save'])->name('content.save');
    Route::resource('pages', AdminPages::class)->parameters(['pages' => 'page:id'])->except('show');
    Route::resource('voitures', AdminCars::class)->parameters(['voitures' => 'car:id'])->names('cars')->except('show');
});

// ===== Pages créées dans l'admin (après toutes les routes existantes) =====
Route::get('/page/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9-]+')->name('page.show');
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('page.catchall');
