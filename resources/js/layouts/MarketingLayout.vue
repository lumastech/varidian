<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Inter:wght@300;400;500;600;700&display=swap"
        />
    </Head>

    <div class="mkt-root">
        <!-- ══════════════ NAV ══════════════ -->
        <nav class="mkt-nav fixed top-0 right-0 left-0 z-50 px-6 py-4">
            <div class="mx-auto flex max-w-6xl items-center justify-between">
                <Link href="/" class="mkt-logo flex items-center gap-2">
                    <AppLogoIcon class=" fill-current text-white dark:text-black" />
                </Link>
                <div class="mkt-nav-pill hidden items-center gap-7 md:flex">
                    <Link href="/" class="mkt-nav-link">Home</Link>
                    <Link href="/services" class="mkt-nav-link">Services</Link>
                    <Link href="/work" class="mkt-nav-link">Work</Link>
                    <Link href="/about" class="mkt-nav-link">About</Link>
                    <Link href="/contact" class="mkt-nav-link">Contact</Link>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden items-center gap-1.5 rounded-full border border-white/10 px-3 py-1.5 text-xs text-slate-400 sm:flex">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                        EN
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                    <a href="https://wa.me/260971864421" target="_blank" rel="noopener noreferrer" class="mkt-btn-wa">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        WhatsApp Us
                    </a>
                </div>
            </div>
        </nav>

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
                            Varidian Consulting Limited — delivering innovative, cost-effective ICT solutions that empower businesses to grow, compete, and succeed.
                        </p>
                        <p class="text-xs text-slate-600">Lusaka, Zambia</p>
                        <p class="mt-1 text-xs text-slate-600">info@varidianlab.com</p>
                    </div>
                    <div>
                        <h4 class="mb-4 text-xs font-semibold tracking-wider text-white uppercase">Services</h4>
                        <ul class="space-y-2.5">
                            <li v-for="link in serviceLinks" :key="link">
                                <Link href="/services" class="text-xs text-slate-500 transition-colors hover:text-slate-300">{{ link }}</Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 text-xs font-semibold tracking-wider text-white uppercase">Company</h4>
                        <ul class="space-y-2.5">
                            <li><Link href="/about" class="text-xs text-slate-500 transition-colors hover:text-slate-300">About Us</Link></li>
                            <li><Link href="/work" class="text-xs text-slate-500 transition-colors hover:text-slate-300">Our Work</Link></li>
                            <li><a href="#" class="text-xs text-slate-500 transition-colors hover:text-slate-300">Careers</a></li>
                            <li><a href="#" class="text-xs text-slate-500 transition-colors hover:text-slate-300">Blog</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 text-xs font-semibold tracking-wider text-white uppercase">Contact</h4>
                        <ul class="space-y-2.5">
                            <li>
                                <a href="mailto:info@varidianlab.com" class="text-xs text-slate-500 transition-colors hover:text-slate-300">info@varidianlab.com</a>
                            </li>
                            <li><span class="text-xs text-slate-500">Lusaka, Zambia</span></li>
                            <li>
                                <a href="https://wa.me/260971864421" target="_blank" class="text-xs text-slate-500 transition-colors hover:text-slate-300">WhatsApp Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mkt-sep mb-6"></div>
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <p class="text-xs text-slate-600">© 2026 Varidian Consulting Limited. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a v-for="s in socials" :key="s" href="#" class="text-xs text-slate-600 transition-colors hover:text-slate-300">{{ s }}</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script lang="ts">
export default {
    data() {
        return {
            serviceLinks: [
                'Custom Software Development',
                'Web & Mobile Apps',
                'Payment Integration',
                'Cybersecurity',
                'Data Analytics & AI',
                'ICT Consulting',
            ],
            socials: ['LinkedIn', 'Twitter / X', 'GitHub'],
        };
    },
};
</script>

<style>
/* ── Marketing root ── */
.mkt-root {
    font-family: 'Inter', sans-serif;
    background: #080c10;
    color: #c8d0db;
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
    background: rgba(8, 12, 16, 0.7);
    backdrop-filter: blur(18px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.mkt-logo {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-weight: 700;
    color: white;
    font-size: 18px;
    letter-spacing: -0.02em;
    text-decoration: none;
    display: inline-block;
}
.mkt-nav-pill {
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: rgba(255, 255, 255, 0.03);
    border-radius: 999px;
    padding: 5px 18px;
}
.mkt-nav-link {
    font-size: 13px;
    color: #8898aa;
    transition: color 0.2s;
    text-decoration: none;
    cursor: pointer;
}
.mkt-nav-link:hover {
    color: #e2e8f0;
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
    background: #080c10;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

/* ── Common shared utilities ── */
.mkt-sep {
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.07), transparent);
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
    color: #eef2f7;
    line-height: 1.15;
}
.mkt-sec-title em {
    font-style: normal;
    color: #8898aa;
}
.mkt-page-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(32px, 5vw, 60px);
    font-weight: 800;
    line-height: 1.08;
    color: #eef2f7;
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
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 999px;
    padding: 10px 26px;
    font-size: 14px;
    color: #c8d0db;
    background: rgba(255, 255, 255, 0.04);
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    display: inline-block;
    text-decoration: none;
}
.mkt-btn-outline:hover {
    border-color: rgba(255, 255, 255, 0.35);
    background: rgba(255, 255, 255, 0.07);
}
.mkt-cta-bottom {
    background: radial-gradient(ellipse 70% 70% at 50% 100%, rgba(0, 180, 160, 0.18) 0%, transparent 70%), rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 20px;
}
.mkt-page-header {
    background: radial-gradient(ellipse 80% 50% at 50% -5%, rgba(0, 201, 167, 0.12) 0%, transparent 65%), #080c10;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.mkt-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
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
