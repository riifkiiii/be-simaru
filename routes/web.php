<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RuangController;

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



Route::get('/', [AuthController::class, 'dashboard']);
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');



Route::middleware(['role:admin'])->group(
    function () {
        Route::get('ruangans', [RuangController::class, 'indexPage'])->name('ruangans.page');
        Route::resource('users', UserController::class);
    }
);

Route::get('bookings', [BookingController::class, 'indexPage'])->name('bookings.page');

Route::get('/booked-dates', [BookingController::class, 'getBookedDates'])->name('get.booked.dates');
Route::post('/booking-ruangan', [BookingController::class, 'submitBooking'])->name('bookings.submit');
