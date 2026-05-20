<?php

use App\Http\Controllers\Web\Backend\Credentials\CredentialsController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\Export\ExportController;
use App\Http\Controllers\Web\Backend\FAQ\FAQController;
use App\Http\Controllers\Web\Backend\Pages\DynamicPagesController;
use App\Http\Controllers\Web\Backend\Settings\SystemSettingsController;
use App\Http\Controllers\Web\Backend\SubscriberController;
use App\Http\Controllers\Web\Backend\BookingsController;
use App\Http\Controllers\Web\Backend\ChatsController;
use App\Http\Controllers\Web\Backend\ReportsController;
use App\Http\Controllers\Web\Backend\ReviewsController;
use App\Http\Controllers\Web\Backend\TripsController;
use App\Http\Controllers\Web\Backend\UploadController;
use App\Http\Controllers\Web\Backend\users\usercontroller;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'web',
        'localize',
        'localizationRedirect',
        'localeSessionRedirect',
        'localeViewPath'
    ]
], function () {
    Route::prefix('admin')->middleware('auth:sanctum', 'admin')->group(function () {

        // Chunk Upload component url 
        Route::post('/upload/chunk', [UploadController::class, 'chunk'])
            ->name('admin.upload.chunk');


        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('admin.dashboard.index');
        });
        Route::controller(DynamicPagesController::class)->group(function () {
            Route::get('/pages', 'index')->name('admin.pages.index');
            Route::get('/pages/edit/{id}', 'edit')->name('admin.pages.edit');
            Route::get('/pages/delete/{id}', 'destroy')->name('admin.pages.edit');
            Route::put('/pages/update/{id}', 'update')->name('admin.pages.update');
        });

        Route::controller(SystemSettingsController::class)->group(function () {
            Route::get('/system/settings', 'index')->name('admin.dashboard.system.settings');
            Route::post('/system/settings', 'SystemUpdate')->name('admin.dashboard.system.settings.update');
        });

        Route::controller(usercontroller::class)->prefix('users')->group(function () {
            Route::get('list', 'userlist')->name('admin.users.index');
            Route::get('create', 'usercreate')->name('admin.users.create');
            Route::post('store', 'userstore')->name('admin.users.store');
            Route::get('edit/{id}', 'useredit')->name('admin.users.edit');
            Route::put('update/{id}', 'userupdate')->name('admin.users.update');
        });

        Route::controller(CredentialsController::class)->prefix('credentials')->group(function () {
            Route::get('/{service}/edit', 'edit')->name('admin.credentials.edit');
            Route::put('/{service}', 'update')->name('admin.credentials.update');
        });

        Route::controller(FAQController::class)
            ->prefix('faq')
            ->name('admin.faq.')
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::get('/edit/{faq}', 'edit')->name('edit');
                Route::put('/update/{faq}', 'update')->name('update');

                Route::delete('/delete/{faq}', 'destroy')->name('delete');
            });


        Route::get('/subscribers', [SubscriberController::class, 'index'])->name('admin.subscribers.index');
        Route::delete('/subscribers/{id}', [SubscriberController::class, 'delete'])->name('admin.subscribers.delete');

        Route::controller(TripsController::class)->prefix('trips')->group(function () {
            Route::get('/list', 'index')->name('admin.trips.index');
            Route::get('/edit/{id}', 'edit')->name('admin.trips.edit');
            Route::get('/show/{id}', 'show')->name('admin.trips.show');
            Route::post('/{id}/status', 'updateStatus')->name('admin.trips.status');
            Route::put('/update/{id}', 'update')->name('admin.trips.update');
            Route::delete('/delete/{id}', 'delete')->name('admin.trips.delete');
        });

        Route::controller(BookingsController::class)->prefix('bookings')->group(function () {
            Route::get('/', 'index')->name('admin.bookings.index');
            Route::get('/edit/{id}', 'edit')->name('admin.bookings.edit');
            Route::put('/update/{id}', 'update')->name('admin.bookings.update');
            Route::delete('/{id}', 'delete')->name('admin.bookings.delete');
        });

        Route::controller(ChatsController::class)->prefix('chats')->group(function () {
            Route::get('/', 'index')->name('admin.chats.index');
            Route::get('/{id}', 'show')->name('admin.chats.show');
        });

        Route::controller(ReportsController::class)->prefix('reports')->group(function () {
            Route::get('/', 'index')->name('admin.reports.index');
        });

        Route::controller(ReviewsController::class)->prefix('reviews')->group(function () {
            Route::get('/', 'index')->name('admin.reviews.index');
            Route::delete('/{id}', 'delete')->name('admin.reviews.delete');
        });

        Route::get('/export/users', [ExportController::class, 'users'])
            ->name('admin.export.users');

        Route::get('/export/trips', [ExportController::class, 'trips'])
            ->name('admin.export.trips');

        Route::get('/export/bookings', [ExportController::class, 'bookings'])
            ->name('admin.export.bookings');

        Route::get('/export/revenue', [ExportController::class, 'revenue'])
            ->name('admin.export.revenue');

        Route::get('/export/chats', [ExportController::class, 'chats'])
            ->name('admin.export.chats');
    });
});
