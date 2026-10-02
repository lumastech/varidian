<?php

use Inertia\Testing\AssertableInertia;

/**
 * @return list<string>
 */
function landingPageSource(): array
{
    return [file_get_contents(resource_path('js/pages/Welcome.vue'))];
}

test('landing page renders the Welcome component', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Welcome'));
});

test('landing page renders every section of the homepage design', function () {
    [$source] = landingPageSource();

    expect($source)
        ->toContain('id="top"')
        ->toContain('id="services"')
        ->toContain('id="products"')
        ->toContain('id="about"')
        ->toContain('id="hosting"')
        ->toContain('id="work"')
        ->toContain('id="contact"')
        ->toContain('Software built for the way')
        ->toContain('Integrated software solutions')
        ->toContain('How <em>we work</em>');
});

test('landing page keeps the WhatsApp calls to action', function () {
    [$source] = landingPageSource();

    expect(substr_count($source, '<WhatsAppButton'))->toBeGreaterThanOrEqual(3);
});

test('landing page enquiry form posts every field the contact request requires', function () {
    [$source] = landingPageSource();
    $form = file_get_contents(resource_path('js/components/marketing/EnquiryForm.vue'));

    expect($source)->toContain('<EnquiryForm');
    expect($form)->toContain("form.post('/contact'");

    foreach (['name', 'organisation', 'phone', 'email', 'product_interest', 'message', 'consent'] as $field) {
        expect($form)->toContain("v-model=\"form.{$field}\"");
    }
});

test('landing enquiry form submissions are accepted by the contact endpoint', function () {
    $this->post(route('contact.store'), [
        'name' => 'Mwila Banda',
        'organisation' => 'Lusaka Academy',
        'phone' => '+260971000000',
        'email' => 'mwila@example.com',
        'product_interest' => 'Web Hosting',
        'message' => 'We would like hosting for our school website.',
        'consent' => true,
    ])->assertRedirect()->assertSessionHasNoErrors();
});

test('landing page does not reference photos removed from public', function () {
    [$source] = landingPageSource();

    expect($source)->not->toContain('/images/landing/');
});

test('landing page no longer renders the ZRA compliance badge', function () {
    [$source] = landingPageSource();

    expect($source)
        ->not->toContain('zra-badge')
        ->not->toContain("badge: 'ZRA Smart Invoice compliant'")
        ->not->toContain('product.badge');
});

test('marketing nav hides the hamburger button on desktop widths', function () {
    $layout = file_get_contents(resource_path('js/layouts/MarketingLayout.vue'));

    // `.mkt-hamburger` sets its own display, which overrides Tailwind's layered `lg:hidden`.
    expect($layout)->toMatch('#@media \(min-width: 1024px\) \{\s*\.mkt-hamburger \{\s*display: none;#');
});

test('landing background images exist in public', function (string $constant) {
    [$source] = landingPageSource();

    preg_match("#const {$constant} = '([^']+)'#", $source, $matches);

    expect($matches)->toHaveKey(1)
        ->and(file_exists(public_path(ltrim($matches[1], '/'))))->toBeTrue();
})->with(['heroImage', 'hostingImage', 'contactImage']);

test('marketing page heroes default to the shared hero background image', function () {
    $hero = file_get_contents(resource_path('js/components/marketing/MarketingPageHero.vue'));

    expect($hero)->toContain("image: '/images/hero-bg.png'")
        ->and(file_exists(public_path('images/hero-bg.png')))->toBeTrue();
});

test('product page headers render the shared hero background image', function (string $page) {
    $source = file_get_contents(resource_path("js/pages/marketing/products/{$page}.vue"));

    expect($source)
        ->toContain('mkt-page-header--photo')
        ->toContain('src="/images/hero-bg.png"');
})->with(['BizManager', 'ChurchManagement', 'SchoolManagement', 'VillageBanking']);

test('dark marketing bands render the shared hero background image', function () {
    $services = file_get_contents(resource_path('js/pages/marketing/Services.vue'));
    $ctaBand = file_get_contents(resource_path('js/components/marketing/MarketingCtaBand.vue'));

    expect($services)->toContain('src="/images/hero-bg.png"')->toContain('v-band-shade')
        ->and($ctaBand)->toContain("image: '/images/hero-bg.png'")->toContain('v-cta-shade');
});
