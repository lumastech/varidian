<?php

use App\Mail\ContactInquiryMail;
use Illuminate\Notifications\Messages\MailMessage;

test('notification emails include the varidian logo', function () {
    $html = (string) (new MailMessage)->line('Hello')->render();

    expect($html)
        ->toContain(asset('images/email-logo.png'))
        ->not->toContain('laravel.com/img/notification-logo');
});

test('contact inquiry email includes the varidian logo and inquiry details', function () {
    $mailable = new ContactInquiryMail([
        'name' => 'John Banda',
        'phone' => '+260971000000',
        'email' => 'john@example.com',
        'organisation' => 'Lusaka Academy',
        'product_interest' => 'Web hosting',
        'message' => 'I would like to discuss a project.',
    ]);

    $mailable->assertSeeInHtml(asset('images/email-logo.png'), false);
    $mailable->assertSeeInHtml('John Banda');
    $mailable->assertSeeInText('Product interest: Web hosting');
});

test('email logo asset exists', function () {
    expect(public_path('images/email-logo.png'))->toBeFile();
});
