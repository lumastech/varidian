<?php

use App\Mail\ContactInquiryMail;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia;

/**
 * @return array<string, mixed>
 */
function validEnquiry(array $overrides = []): array
{
    return array_merge([
        'name' => 'John Banda',
        'organisation' => 'Lusaka Academy',
        'email' => 'john@example.com',
        'phone' => '+260971000000',
        'product_interest' => 'Web hosting',
        'message' => 'I would like to discuss a project.',
        'consent' => true,
    ], $overrides);
}

test('contact page renders', function () {
    $this->get('/contact')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('marketing/Contact'));
});

test('contact form stores a valid submission', function () {
    Mail::fake();

    $this->post('/contact', validEnquiry())
        ->assertRedirect()
        ->assertSessionHas('status', 'message-sent');

    Mail::assertSent(ContactInquiryMail::class, fn (ContactInquiryMail $mail) => $mail->data['product_interest'] === 'Web hosting');
});

test('contact form rejects missing required fields', function () {
    $this->post('/contact', [])
        ->assertSessionHasErrors(['name', 'phone', 'product_interest', 'message', 'consent']);
});

test('contact form rejects invalid email', function () {
    $this->post('/contact', validEnquiry(['email' => 'not-an-email']))
        ->assertSessionHasErrors(['email']);
});

test('contact form requires consent to store the enquiry', function () {
    Mail::fake();

    $this->post('/contact', validEnquiry(['consent' => false]))
        ->assertSessionHasErrors(['consent']);

    Mail::assertNothingSent();
});

test('marketing pages render', function (string $url, string $component) {
    $this->get($url)
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component($component));
})->with([
    ['/', 'Welcome'],
    ['/about', 'marketing/About'],
    ['/services', 'marketing/Services'],
    ['/products', 'marketing/products/Index'],
    ['/hosting', 'marketing/Hosting'],
    ['/work', 'marketing/Work'],
]);

test('sitemap lists every marketing page', function () {
    $response = $this->get('/sitemap.xml')->assertOk();

    foreach (['/about', '/services', '/products', '/hosting', '/work', '/contact'] as $path) {
        $response->assertSee(url($path), false);
    }
});
