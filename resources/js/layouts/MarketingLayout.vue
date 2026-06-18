<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { ref } from 'vue';

const page = usePage();
const mobileOpen = ref(false);
const productsOpen = ref(false);

const isActive = (href: string): boolean => {
    if (href === '/') return page.url === '/';
    return page.url.startsWith(href);
};

const navLinks = [
    { href: '/', label: 'Home' },
    { href: '/products', label: 'Products', hasDropdown: true },
    { href: '/about', label: 'About' },
    { href: '/contact', label: 'Contact' },
];

const productLinks = [
    { href: '/products/school-management-system', label: 'School Management System' },
    { href: '/products/church-management-system', label: 'Church Management System' },
    { href: '/products/bizmanager', label: 'BizManager' },
    { href: '/products/village-banking', label: 'Village Banking' },
];

const footerProductLinks = [
    { href: '/products/school-management-system', label: 'School Management System' },
    { href: '/products/church-management-system', label: 'Church Management System' },
    { href: '/products/bizmanager', label: 'BizManager' },
    { href: '/products/village-banking', label: 'Village Banking' },
];

const jsonLd = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'SoftwareApplication',
    name: 'Varidian Consulting Limited',
    url: 'https://varidianlab.com',
    description: 'Sector-specific management systems for Zambian schools, churches, NGOs, and businesses.',
    applicationCategory: 'BusinessApplication',
    operatingSystem: 'Web',
    offers: {
        '@type': 'Offer',
        priceCurrency: 'ZMW',
        availability: 'https://schema.org/InStock',
    },
    provider: {
        '@type': 'Organization',
        name: 'Varidian Consulting Limited',
        url: 'https://varidianlab.com',
        logo: 'https://varidianlab.com/images/logo.png',
        address: {
            '@type': 'PostalAddress',
            addressLocality: 'Lusaka',
            addressCountry: 'ZM',
        },
        contactPoint: {
            '@type': 'ContactPoint',
            contactType: 'customer support',
            availableLanguage: 'English',
        },
    },
});
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Inter:wght@300;400;500;600;700&display=swap"
        />
        <!-- eslint-disable-next-line vue/no-v-html -->
        <component :is="'script'" type="application/ld+json" v-html="jsonLd" />
    </Head>

    <div class="mkt-root">
        <!-- ══════════════ NAV ══════════════ -->
        <div class="fixed top-0 right-0 left-0 z-50">
            <nav class="mkt-nav px-6 py-4">
                <div class="mx-auto flex max-w-6xl items-center justify-between">
                    <Link href="/" class="mkt-logo flex items-center gap-2">
                        <AppLogoIcon class="fill-current text-white dark:text-black h-2" />
                    </Link>

                    <!-- Desktop links -->
                    <div class="mkt-nav-pill hidden items-center gap-7 md:flex">
                        <template v-for="link in navLinks" :key="link.href">
                            <!-- Products with dropdown -->
                            <div v-if="link.hasDropdown" class="relative" @mouseenter="productsOpen = true" @mouseleave="productsOpen = false">
                                <Link
                                    :href="link.href"
                                    :class="['mkt-nav-link flex items-center gap-1', { 'mkt-nav-link--active': isActive(link.href) }]"
                                >
                                    {{ link.label }}
                                    <svg class="h-3 w-3 transition-transform" :class="productsOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7" />
                                    </svg>
                                </Link>
                                <Transition name="mkt-dropdown">
                                    <div v-if="productsOpen" class="mkt-dropdown absolute top-full left-1/2 -translate-x-1/2 pt-3">
                                        <div class="mkt-dropdown-menu min-w-56">
                                            <Link
                                                v-for="p in productLinks"
                                                :key="p.href"
                                                :href="p.href"
                                                class="mkt-dropdown-item"
                                                @click="productsOpen = false"
                                            >
                                                {{ p.label }}
                                            </Link>
                                        </div>
                                    </div>
                                </Transition>
                            </div>
                            <!-- Regular links -->
                            <Link
                                v-else
                                :href="link.href"
                                :class="['mkt-nav-link', { 'mkt-nav-link--active': isActive(link.href) }]"
                            >{{ link.label }}</Link>
                        </template>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- WhatsApp CTA (desktop) -->
                        <a href="https://wa.me/260971864421?text=Hi%2C%20I%27d%20like%20to%20learn%20more%20about%20Varidian%20products." target="_blank" rel="noopener noreferrer" class="mkt-btn-wa hidden md:inline-flex">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            <span class="hidden md:flex">Get in touch</span>
                        </a>

                        <!-- Hamburger (mobile) -->
                        <button class="mkt-hamburger md:hidden" :aria-expanded="mobileOpen" aria-label="Toggle menu" @click="mobileOpen = !mobileOpen">
                            <svg v-if="!mobileOpen" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Mobile menu -->
            <Transition name="mkt-slide">
                <div v-if="mobileOpen" class="mkt-mobile-menu md:hidden">
                    <Link
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        :class="['mkt-mobile-link', { 'mkt-mobile-link--active': isActive(link.href) }]"
                        @click="mobileOpen = false"
                    >
                        {{ link.label }}
                        <svg v-if="isActive(link.href)" class="ml-auto h-4 w-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                    <!-- Products sub-links on mobile -->
                    <div class="mt-1 mb-3 space-y-1 pl-4">
                        <Link
                            v-for="p in productLinks"
                            :key="p.href"
                            :href="p.href"
                            class="block py-2 text-xs text-slate-500 transition-colors hover:text-slate-300"
                            @click="mobileOpen = false"
                        >
                            → {{ p.label }}
                        </Link>
                    </div>
                    <div class="mt-5 px-1">
                        <a href="https://wa.me/260971864421?text=Hi%2C%20I%27d%20like%20to%20learn%20more%20about%20Varidian%20products." target="_blank" rel="noopener noreferrer" class="mkt-btn-wa w-full justify-center" @click="mobileOpen = false">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Get in touch
                        </a>
                    </div>
                </div>
            </Transition>
        </div>

        <!-- ══════════════ PAGE CONTENT ══════════════ -->
        <slot />

        <!-- ══════════════ FOOTER ══════════════ -->
        <footer class="mkt-footer px-6 pb-10">
            <div class="mx-auto max-w-6xl pt-12">
                <div class="mb-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <div class="mkt-logo mb-3">
                            <AppLogoIcon class="w-20 fill-current text-white dark:text-black" />
                        </div>
                        <p class="mb-4 max-w-xs text-xs leading-relaxed text-slate-500">
                            Varidian Consulting Limited — sector-specific management systems built for Zambian schools, churches, NGOs, and businesses.
                        </p>
                        <p class="text-xs text-slate-600">Registered in Zambia</p>
                        <p class="mt-1 text-xs text-slate-600">PACRA Reg. No: 120261044865</p>
                    </div>
                    <div>
                        <h4 class="mb-4 text-xs font-semibold tracking-wider text-white uppercase">Products</h4>
                        <ul class="space-y-2.5">
                            <li v-for="link in footerProductLinks" :key="link.href">
                                <Link :href="link.href" class="text-xs text-slate-500 transition-colors hover:text-slate-300">{{ link.label }}</Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 text-xs font-semibold tracking-wider text-white uppercase">Company</h4>
                        <ul class="space-y-2.5">
                            <li><Link href="/about" class="text-xs text-slate-500 transition-colors hover:text-slate-300">About Us</Link></li>
                            <li><Link href="/products" class="text-xs text-slate-500 transition-colors hover:text-slate-300">Our Products</Link></li>
                            <li><Link href="/contact" class="text-xs text-slate-500 transition-colors hover:text-slate-300">Contact Us</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 text-xs font-semibold tracking-wider text-white uppercase">Contact</h4>
                        <ul class="space-y-2.5">
                            <li><span class="text-xs text-slate-500">Lusaka, Zambia</span></li>
                            <li><a href="tel:+260971864421" class="text-xs text-slate-500 transition-colors hover:text-slate-300">+260 97 1864421</a></li>
                            <li>
                                <a href="mailto:info@varidianlab.com" class="text-xs text-slate-500 transition-colors hover:text-slate-300">info@varidianlab.com</a>
                            </li>
                            <li>
                                <a href="https://wa.me/260971864421" target="_blank" class="text-xs text-slate-500 transition-colors hover:text-slate-300">WhatsApp: +260 97 1864421</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mkt-sep mb-6"></div>
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <p class="text-xs text-slate-600">© {{ new Date().getFullYear() }} Varidian Consulting Limited. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- ══════════════ FLOATING WHATSAPP ══════════════ -->
        <WhatsAppButton variant="floating" message="Hi, I'd like to learn more about Varidian products." />
    </div>
</template>

<style>
/* ── CSS Custom Properties: dark by default, auto-switches to light ── */
:root {
    --mkt-bg:        #080c10;
    --mkt-bg-2:      #0a0f14;
    --mkt-surface:   rgba(255, 255, 255, 0.03);
    --mkt-surface-2: rgba(255, 255, 255, 0.015);
    --mkt-text:      #c8d0db;
    --mkt-text-h:    #eef2f7;
    --mkt-text-m:    #8898aa;
    --mkt-muted:     #9ba8b8;
    --mkt-line:      rgba(255, 255, 255, 0.07);
    --mkt-line-s:    rgba(255, 255, 255, 0.14);
    --mkt-nav-bg:    rgba(8, 12, 16, 0.75);
    --mkt-dot-grid:  rgba(255, 255, 255, 0.025);
    --mkt-shadow:    0 20px 60px rgba(0, 0, 0, 0.5);
}

@media (prefers-color-scheme: light) {
    :root {
        --mkt-bg:        #f8fafc;
        --mkt-bg-2:      #f1f5f9;
        --mkt-surface:   #ffffff;
        --mkt-surface-2: #f8fafc;
        --mkt-text:      #334155;
        --mkt-text-h:    #0f172a;
        --mkt-text-m:    #64748b;
        --mkt-muted:     #94a3b8;
        --mkt-line:      rgba(0, 0, 0, 0.08);
        --mkt-line-s:    rgba(0, 0, 0, 0.16);
        --mkt-nav-bg:    rgba(248, 250, 252, 0.88);
        --mkt-dot-grid:  rgba(0, 0, 0, 0.045);
        --mkt-shadow:    0 20px 60px rgba(0, 0, 0, 0.12);
    }
}

/* ── Light-mode overrides for Tailwind color utilities ── */
@media (prefers-color-scheme: light) {
    .mkt-root .text-white       { color: var(--mkt-text-h); }
    .mkt-root .text-slate-300   { color: var(--mkt-text); }
    .mkt-root .text-slate-400   { color: var(--mkt-text-m); }
    .mkt-root .text-slate-500   { color: var(--mkt-text-m); }
    .mkt-root .text-slate-600   { color: var(--mkt-text-m); }
    .mkt-root .border-white\/5  { border-color: rgba(0, 0, 0, 0.06); }
    .mkt-root .border-white\/10 { border-color: rgba(0, 0, 0, 0.1); }
    .mkt-root::before           { opacity: 0.12; }
}

/* ── Marketing root ── */
.mkt-root {
    font-family: 'Inter', sans-serif;
    background: var(--mkt-bg);
    color: var(--mkt-text);
    overflow-x: hidden;
    min-height: 100vh;
}

/* ── Noise grain overlay ── */
.mkt-root::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 0;
    opacity: 0.4;
}

/* ── Nav ── */
.mkt-nav {
    background: var(--mkt-nav-bg);
    backdrop-filter: blur(18px);
    border-bottom: 1px solid var(--mkt-line);
}
.mkt-logo {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-weight: 700;
    color: var(--mkt-text-h);
    font-size: 18px;
    letter-spacing: -0.02em;
    text-decoration: none;
    display: inline-block;
}
.mkt-nav-pill {
    border: 1px solid var(--mkt-line-s);
    background: var(--mkt-surface);
    border-radius: 999px;
    padding: 5px 18px;
}
.mkt-nav-link {
    font-size: 13px;
    color: var(--mkt-text-m);
    transition: color 0.2s;
    text-decoration: none;
    cursor: pointer;
    position: relative;
    padding-bottom: 2px;
}
.mkt-nav-link:hover {
    color: var(--mkt-text-h);
}
.mkt-nav-link--active {
    color: var(--mkt-text-h);
}
.mkt-nav-link--active::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #00c9a7;
}

/* ── Products dropdown ── */
.mkt-dropdown-menu {
    background: var(--mkt-bg-2);
    backdrop-filter: blur(20px);
    border: 1px solid var(--mkt-line);
    border-radius: 12px;
    padding: 6px;
    box-shadow: var(--mkt-shadow);
}
.mkt-dropdown-item {
    display: block;
    padding: 9px 14px;
    font-size: 13px;
    color: var(--mkt-text-m);
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    white-space: nowrap;
}
.mkt-dropdown-item:hover {
    background: rgba(0, 201, 167, 0.1);
    color: var(--mkt-text-h);
}
.mkt-dropdown-enter-active,
.mkt-dropdown-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}
.mkt-dropdown-enter-from,
.mkt-dropdown-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}

/* ── Hamburger ── */
.mkt-hamburger {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 8px;
    color: var(--mkt-text);
    padding: 6px;
    cursor: pointer;
    transition: background 0.2s, border-color 0.2s;
}
.mkt-hamburger:hover {
    border-color: var(--mkt-line-s);
}

/* ── Mobile menu ── */
.mkt-mobile-menu {
    background: var(--mkt-bg);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--mkt-line);
    padding: 8px 24px 24px;
}
.mkt-mobile-link {
    display: flex;
    align-items: center;
    font-size: 15px;
    font-weight: 500;
    color: var(--mkt-text-m);
    text-decoration: none;
    padding: 14px 0;
    border-bottom: 1px solid var(--mkt-line);
    transition: color 0.2s;
}
.mkt-mobile-link:last-of-type {
    border-bottom: none;
}
.mkt-mobile-link:hover {
    color: var(--mkt-text-h);
}
.mkt-mobile-link--active {
    color: var(--mkt-text-h);
}

/* ── Mobile menu slide transition ── */
.mkt-slide-enter-active,
.mkt-slide-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.mkt-slide-enter-from,
.mkt-slide-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

.mkt-btn-wa {
    background: #25d366;
    border-radius: 999px;
    padding: 7px 20px;
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    transition: opacity 0.2s, transform 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}
.mkt-btn-wa:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
.mkt-btn-wa-lg {
    padding: 12px 28px;
    font-size: 14px;
}

/* ── Footer ── */
.mkt-footer {
    background: var(--mkt-bg-2);
    border-top: 1px solid var(--mkt-line);
}

/* ── Common shared utilities ── */
.mkt-sec-dark { background: var(--mkt-bg); }
.mkt-sec-alt  { background: var(--mkt-bg-2); }
.mkt-sep {
    height: 1px;
    background: linear-gradient(to right, transparent, var(--mkt-line-s), transparent);
}
.mkt-chip {
    display: inline-block;
    background: rgba(0, 201, 167, 0.1);
    border: 1px solid rgba(0, 201, 167, 0.25);
    color: #00c9a7;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    border-radius: 999px;
    padding: 3px 10px;
}
.mkt-sec-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(28px, 4vw, 48px);
    font-weight: 800;
    color: var(--mkt-text-h);
    line-height: 1.15;
}
.mkt-sec-title em {
    font-style: normal;
    color: var(--mkt-text-m);
}
.mkt-page-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(32px, 5vw, 60px);
    font-weight: 800;
    line-height: 1.08;
    color: var(--mkt-text-h);
}
.mkt-page-title .accent {
    background: linear-gradient(90deg, #00c9a7, #00a3ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.mkt-btn-teal {
    background: linear-gradient(135deg, #00c9a7, #00a3ff);
    border-radius: 999px;
    padding: 10px 28px;
    font-size: 14px;
    font-weight: 600;
    color: #080c10;
    display: inline-block;
    text-decoration: none;
    transition: opacity 0.2s;
    cursor: pointer;
    border: none;
}
.mkt-btn-teal:hover {
    opacity: 0.88;
}
.mkt-btn-outline {
    border: 1px solid var(--mkt-line-s);
    border-radius: 999px;
    padding: 10px 26px;
    font-size: 14px;
    color: var(--mkt-text);
    background: var(--mkt-surface);
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    display: inline-block;
    text-decoration: none;
}
.mkt-btn-outline:hover {
    border-color: #00c9a7;
}
.mkt-cta-bottom {
    background: radial-gradient(ellipse 70% 70% at 50% 100%, rgba(0, 180, 160, 0.15) 0%, transparent 70%), var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 20px;
}
.mkt-page-header {
    background: radial-gradient(ellipse 80% 50% at 50% -5%, rgba(0, 201, 167, 0.1) 0%, transparent 65%), var(--mkt-bg);
    border-bottom: 1px solid var(--mkt-line);
}
.mkt-card {
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 14px;
    transition: border-color 0.25s;
}
.mkt-card:hover {
    border-color: rgba(0, 201, 167, 0.22);
}
.mkt-service-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(0, 201, 167, 0.1);
    border: 1px solid rgba(0, 201, 167, 0.2);
    margin-bottom: 16px;
    flex-shrink: 0;
}
/* ── Scroll reveal ── */
[data-reveal] {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.65s ease, transform 0.65s ease;
}
[data-reveal].in {
    opacity: 1;
    transform: none;
}
</style>
