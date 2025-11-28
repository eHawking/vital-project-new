<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoyaltyBonusController;
use App\Http\Controllers\User\Auth\SSOController;

// API Route for Reverse SSO Token Verification (main script calls this)
Route::post('/api/sso/verify-reverse-token', [SSOController::class, 'verifyReverseToken'])->name('api.sso.verify-reverse-token');

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('/distribute-bonus', [RoyaltyBonusController::class, 'distributeBonusCron'])->name('distribute_bonus');
Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->middleware('auth')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});


Route::controller('SiteController')->middleware('auth')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('/products/{catId?}', 'products')->name('products');
    Route::get('/product/{id}/{slug}', 'productDetails')->name('product.details');

    Route::get('/blog', 'blog')->name('blog');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('faq', 'faq')->name('faq');

    Route::post('/check/referral', 'checkUsername')->withoutMiddleware('auth')->name('check.referral');
    Route::post('/get/user/position', 'userPosition')->withoutMiddleware('auth')->name('get.user.position');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
