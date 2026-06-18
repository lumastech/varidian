<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::inertia('/about', 'marketing/About')->name('about');
Route::inertia('/services', 'marketing/Services')->name('services');
Route::inertia('/work', 'marketing/Work')->name('work');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::middleware('throttle:5,1')->post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Products
Route::inertia('/products', 'marketing/products/Index')->name('products');
Route::inertia('/products/school-management-system', 'marketing/products/SchoolManagement')->name('products.zssms');
Route::inertia('/products/church-management-system', 'marketing/products/ChurchManagement')->name('products.cms');
Route::inertia('/products/bizmanager', 'marketing/products/BizManager')->name('products.bizmanager');
Route::inertia('/products/village-banking', 'marketing/products/VillageBanking')->name('products.village-banking');

// Sitemap
Route::get('/sitemap.xml', function () {
    $lastmod = now()->toDateString();

    $urls = [
        ['loc' => url('/'), 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => url('/about'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/products'), 'changefreq' => 'weekly', 'priority' => '0.9'],
        ['loc' => url('/products/school-management-system'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/products/church-management-system'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/products/bizmanager'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/products/village-banking'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/contact'), 'changefreq' => 'monthly', 'priority' => '0.7'],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>'.htmlspecialchars($url['loc']).'</loc>';
        $xml .= '<lastmod>'.$lastmod.'</lastmod>';
        $xml .= '<changefreq>'.$url['changefreq'].'</changefreq>';
        $xml .= '<priority>'.$url['priority'].'</priority>';
        $xml .= '</url>';
    }
    $xml .= '</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::get('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
});

require __DIR__.'/settings.php';
