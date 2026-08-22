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

test('every landing photo referenced by the page exists in public', function () {
    [$source] = landingPageSource();

    preg_match_all("#'(/images/landing/[^']+)'#", $source, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach ($matches[1] as $path) {
        expect(public_path($path))->toBeFile("Missing landing image: {$path}");
    }
});

test('landing page no longer renders the ZRA compliance badge', function () {
    [$source] = landingPageSource();

    // The FAQ still answers the ZRA question; only the unverified product badge is gone.
    expect($source)
        ->not->toContain('zra-badge')
        ->not->toContain("badge: 'ZRA Smart Invoice compliant'")
        ->not->toContain('product.badge')
        ->toContain('Are your products ZRA Smart Invoice compliant?');
});

test('landing photos are not crushed to invisibility by their own styling', function () {
    [$source] = landingPageSource();

    // Regression guard: the hero/band/duotone photos were each rendered at a low
    // `opacity` *and* dimmed by `brightness()` *and* buried under a near-opaque
    // overlay, leaving only a few percent of the photo in the final pixel.
    preg_match_all('#\.(v-hero-photo|v-band-photo|v-duo) img \{(.+?)\}#s', $source, $layers, PREG_SET_ORDER);

    expect($layers)->toHaveCount(3);

    foreach ($layers as [, $selector, $block]) {
        $opacity = preg_match('#opacity:\s*([\d.]+)#', $block, $m) ? (float) $m[1] : 1.0;
        $brightness = preg_match('#brightness\(([\d.]+)\)#', $block, $m) ? (float) $m[1] : 1.0;

        expect($opacity * $brightness)
            ->toBeGreaterThanOrEqual(0.7, "Photo layer .{$selector} is dimmed to the point of disappearing");
    }

    // The full-bleed veils painted over those photos must stay translucent enough
    // to see through. Each capture is an `rgba(...)` alpha inside a veil gradient.
    preg_match_all('#\.(v-hero-photo|v-band-photo)::after \{(.+?)\}#s', $source, $veils, PREG_SET_ORDER);

    expect($veils)->toHaveCount(2);

    foreach ($veils as [, $selector, $block]) {
        preg_match_all('#rgba\(\d+,\s*\d+,\s*\d+,\s*([\d.]+)\)#', $block, $alphas);

        expect(max(array_map('floatval', $alphas[1])))
            ->toBeLessThanOrEqual(0.9, "Veil on .{$selector} is opaque enough to hide the photo behind it");
    }
});
