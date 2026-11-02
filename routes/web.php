<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\LeadReviewController;
use App\Http\Controllers\Admin\OfflinePaymentController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Billing\CheckoutController;
use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\Billing\InvoicePrintController;
use App\Http\Controllers\Billing\PaymentController;
use App\Http\Controllers\Billing\PlanChangeController;
use App\Http\Controllers\Billing\SubscriptionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HostingController;
use App\Http\Controllers\Partners\LeadController;
use App\Http\Controllers\Partners\PartnerApplicationController;
use App\Http\Controllers\Partners\PartnerDashboardController;
use App\Http\Controllers\Partners\PartnerProgrammeController;
use App\Http\Controllers\Partners\PayoutStatementController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Controllers\Webhooks\LencoWebhookController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');

Route::inertia('/about', 'marketing/About')->name('about');
Route::inertia('/services', 'marketing/Services')->name('services');
Route::inertia('/work', 'marketing/Work')->name('work');
Route::get('/hosting', HostingController::class)->name('hosting');
Route::get('/partners', PartnerProgrammeController::class)->name('partners');
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
        ['loc' => url('/services'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/hosting'), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => url('/work'), 'changefreq' => 'monthly', 'priority' => '0.7'],
        ['loc' => url('/products'), 'changefreq' => 'weekly', 'priority' => '0.9'],
        ['loc' => url('/products/school-management-system'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/products/church-management-system'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/products/bizmanager'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/products/village-banking'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => url('/partners'), 'changefreq' => 'monthly', 'priority' => '0.6'],
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
        Route::get('dashboard', DashboardController::class)->name('dashboard');

        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->scopeBindings()->name('invoices.show');
        Route::get('invoices/{invoice}/print', InvoicePrintController::class)->scopeBindings()->name('invoices.print');

        Route::middleware(EnsureTeamMembership::class.':admin')->group(function () {
            Route::get('hosting/checkout/{plan}', [CheckoutController::class, 'create'])->name('checkout.create');
            Route::post('hosting/checkout/{plan}', [CheckoutController::class, 'store'])->name('checkout.store');

            Route::scopeBindings()->group(function () {
                Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])
                    ->middleware('throttle:10,1')
                    ->name('invoices.payments.store');
                Route::post('invoices/{invoice}/payments/{payment}/verify', [PaymentController::class, 'verify'])
                    ->name('invoices.payments.verify');
                Route::get('subscriptions/{subscription}/plan', [PlanChangeController::class, 'edit'])
                    ->name('subscriptions.plan.edit');
                Route::put('subscriptions/{subscription}/plan', [PlanChangeController::class, 'update'])
                    ->name('subscriptions.plan.update');
                Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])
                    ->name('subscriptions.cancel');
            });
        });
    });

Route::middleware(['auth', 'verified'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/', PartnerDashboardController::class)->name('dashboard');
    Route::get('apply', [PartnerApplicationController::class, 'create'])->name('apply');
    Route::post('apply', [PartnerApplicationController::class, 'store'])->name('apply.store');
    Route::post('leads', [LeadController::class, 'store'])->middleware(['can:act-as-partner', 'throttle:20,1'])->name('leads.store');
    Route::get('payouts/{payout}', PayoutStatementController::class)->name('payouts.show');
});

Route::middleware('auth')->get('hosting/{plan}/order', [CheckoutController::class, 'order'])->name('hosting.order');

Route::middleware(['auth', 'verified', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::post('invoices/{invoice}/offline-payments', OfflinePaymentController::class)->name('invoices.offline-payments.store');
    Route::resource('plans', PlanController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('partners/{partner}', [PartnerController::class, 'show'])->name('partners.show');
    Route::put('partners/{partner}/status', [PartnerController::class, 'updateStatus'])->name('partners.status.update');
    Route::post('partners/{partner}/commissions', [CommissionController::class, 'store'])->name('partners.commissions.store');
    Route::post('partners/{partner}/payouts', [PayoutController::class, 'store'])->name('partners.payouts.store');
    Route::put('leads/{lead}', LeadReviewController::class)->name('leads.update');
});

Route::post('webhooks/lenco', LencoWebhookController::class)->name('webhooks.lenco');

Route::middleware(['auth'])->group(function () {
    Route::get('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
});

require __DIR__.'/settings.php';
