<?php

use Inertia\Testing\AssertableInertia;

test('contact page renders', function () {
    $this->get('/contact')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('marketing/Contact'));
});

test('contact form stores a valid submission', function () {
    $this->post('/contact', [
        'name' => 'John Banda',
        'email' => 'john@example.com',
        'message' => 'I would like to discuss a project.',
    ])
        ->assertRedirect()
        ->assertSessionHas('status', 'message-sent');
});

test('contact form rejects missing required fields', function () {
    $this->post('/contact', [])
        ->assertSessionHasErrors(['name', 'email', 'message']);
});

test('contact form rejects invalid email', function () {
    $this->post('/contact', [
        'name' => 'John Banda',
        'email' => 'not-an-email',
        'message' => 'Hello.',
    ])
        ->assertSessionHasErrors(['email']);
});

test('marketing pages render', function (string $url, string $component) {
    $this->get($url)
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component($component));
})->with([
    ['/', 'Welcome'],
    ['/about', 'marketing/About'],
    ['/services', 'marketing/Services'],
    ['/work', 'marketing/Work'],
]);
