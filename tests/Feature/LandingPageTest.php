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
