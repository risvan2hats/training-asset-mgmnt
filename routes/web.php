<?php

use App\Http\Controllers\{
    AssetController,
    AssetHistoryController,
    DashboardController,
    LocationController,
    ProfileController,
    UserController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    // Route::get('/dashboard', [AssetHistoryController::class, 'allHistories'])->name('dashboard');
    Route::get('/dashboard', [AssetHistoryController::class, 'allHistories'])->name('dashboard');

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Assets
    Route::resource('assets', AssetController::class)->except(['move']);
    Route::get('assets/{asset}/move', [AssetController::class, 'moveForm'])->name('assets.move-form');
    Route::post('assets/{asset}/move', [AssetController::class, 'move'])->name('assets.move');
    Route::get('assets/{asset}/histories', [AssetHistoryController::class, 'assetHistories'])->name('assets.histories');

    // Asset Histories
    // Route::get('/asset-histories', [AssetHistoryController::class, 'allHistories'])->name('asset-histories.index');

    // Locations
    Route::resource('locations', LocationController::class);

    // Users
    Route::resource('users', UserController::class);
    Route::get('users/{user}/assets', [UserController::class, 'assets'])->name('users.assets');
    Route::get('users/{user}/history', [UserController::class, 'history'])->name('users.history');
});

require __DIR__ . '/auth.php';
