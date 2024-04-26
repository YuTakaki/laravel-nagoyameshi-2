<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\HomeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');


require __DIR__.'/auth.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
});


Route::controller(RestaurantController::class)->group(function () {
    Route::get('/admin/restaurants/index', 'index')->name('admin.restaurants.index');
    Route::get('/admin/restaurants/show/{restaurant}', 'show')->name('admin.restaurants.show');
    Route::get('/admin/restaurants/edit/{restaurant}', 'edit')->name('admin.restaurants.edit');
    Route::get('/admin/restaurants/create', 'create')->name('admin.restaurants.create');
    Route::post('/admin/restaurants/store', 'store')->name('admin.restaurants.store');
    Route::delete('/admin/restaurants/{restaurant}', 'destroy')->name('admin.restaurants.destroy');
    Route::patch('/admin/restaurants/show/{restaurant}', 'update')->name('admin.restaurants.update');
});

Route::resource('admin/categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.categories');

Route::prefix('admin/company')->group(function () {
    Route::get('/index', [CompanyController::class, 'index'])->name('admin.company.index');
    Route::get('/edit', [CompanyController::class, 'edit'])->name('admin.company.edit');
    Route::patch('/edit', [CompanyController::class, 'update'])->name('admin.company.update');
});

Route::prefix('admin/terms')->group(function () {
    Route::get('/index', [TermController::class, 'index'])->name('admin.terms.index');
    Route::get('/edit', [TermController::class, 'edit'])->name('admin.terms.edit');
    Route::patch('/edit', [TermController::class, 'update'])->name('admin.terms.update');
});

Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});




