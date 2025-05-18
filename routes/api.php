<?php

#region Middleware
use App\Http\Middleware\AdminMiddleware;
#endregion
#region Admin Controller
use App\Http\Controllers\Admin\AdminCinemaController;
use App\Http\Controllers\Admin\AdminMovieController;
use App\Http\Controllers\Admin\AdminShowtimeController;
use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminUserController;
#endregion
#region Auth Controller
use App\Http\Controllers\Auth\AuthController;
#endregion
#region Api Controllers
use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ProductComboController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\GenreController;
use Illuminate\Support\Facades\Route;
#endregion

#region Auth Routes
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/me', 'user');
    });
});
#endregion

#region User Routes (Required Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('bookings', BookingController::class)->only(['index', 'store', 'update']);

    // Profile Management
    /* Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'show');
        Route::put('/', 'update');
        Route::put('/password', 'updatePassword');
    }); */

    // Booking Management
});
#endregion

#region Public Routes
Route::middleware(['check.referer'])->group(
    function () {
        Route::prefix('movies')->controller(MovieController::class)->group(function () {
            Route::get("/", 'index');
            Route::get('/now-showing', 'nowShowing');
            Route::get('/coming-soon', 'comingSoon');
            Route::get('/{slug}', 'show');
            Route::get('/{id}/showtimes', 'getShowtimesById');
        });

        Route::prefix('cinemas')->controller(CinemaController::class)->group(function () {
            Route::get('/{id}', 'show');
            Route::get('/{slug}', 'show');
            Route::get('/{slug}/showtimes', 'getShowtimesByDate');
        });

        Route::prefix('cities')->controller(CityController::class)->group(function () {
            Route::get('/', 'index');
        });

        Route::prefix('combos')->controller(ProductComboController::class)->group(function () {
            Route::get('/', 'index');
        });

        Route::prefix('genres')->controller(GenreController::class)->group(function () {
            Route::get('/', 'index');
        });
    }
);
#endregion

#region Admin Routes
Route::middleware(['auth:sanctum', AdminMiddleware::class])
    ->prefix('admin')
    ->group(function () {
        Route::apiResource('movies', AdminMovieController::class);
        Route::apiResource('users', AdminUserController::class)->except(['store', 'update', 'destroy']);
        Route::apiResource('cinemas', AdminCinemaController::class);
        Route::apiResource('rooms', AdminRoomController::class)->except(['store', 'update']);
        Route::apiResource('showtimes', AdminShowtimeController::class);

        Route::prefix('cinemas')->controller(AdminCinemaController::class)->group(function () {
            Route::get('/{id}/rooms', 'getRooms');
            Route::post('/{id}/rooms', 'storeRoom');
        });
    });
#endregion