<script setup lang="ts">
import { ref, onMounted } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';

const activeFaq = ref<number | null>(0);

const products = [
    {
        title: 'Zambian School Management System (ZSSMS)',
        badge: null,
        desc: 'Grades 1–12 on the MoE three-term calendar. ECZ exam tracking for Grades 7, 9, and 12. Fee collection via Airtel Money and MTN MoMo. NAPSA and PAYE payroll for staff. Built for Zambian private and community schools.',
        href: '/products/school-management-system',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.07a12.083 12.083 0 01-6.16-10.492L12 14z"/></svg>`,
    },
    {
        title: 'Church Management System',
        badge: null,
        desc: 'Member and cell group registry. Tithe and offering tracking with mobile money. Attendance, events, and SMS or WhatsApp notifications to your congregation. Purpose-built for Zambian churches and ministries.',
        href: '/products/church-management-system',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>`,
    },
    {
        title: 'Varidian BizManager',
        badge: 'ZRA Smart Invoice compliant',
        desc: 'A complete business manager for Zambian SMEs. Invoicing, inventory, sales, expenses, and reports — all connected to ZRA Smart Invoice for full compliance. Works on mobile. Supported locally.',
        href: '/products/bizmanager',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>`,
    },
    {
        title: 'Village Banking & Microfinance Platform',
        badge: null,
        desc: 'Group and member management for VSLAs and microfinance organisations. Loan and savings cycle tracking, campaign management, and mobile money disbursements. Built for Zambian NGOs and community finance groups.',
        href: '/products/village-banking',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>`,
    },
];

const whyCards = [
    {
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        heading: 'Built in Zambia, for Zambia',
        body: 'Our systems understand the local context — ZRA compliance, ECZ exams, NAPSA payroll, Airtel and MTN integrations. No offshore vendor offers this depth.',
    },
    {
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>`,
        heading: 'Local support you can reach',
        body: 'Call or WhatsApp a real person in Lusaka. No helpdesk tickets. No international time zones. We are here when something needs fixing.',
    },
    {
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>`,
        heading: 'Systems that grow with you',
        body: 'Start with one module. Add more as your organisation grows. All products are subscription-based with regular updates included.',
    },
    {
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>`,
        heading: 'ZRA compliant from day one',
        body: 'Varidian BizManager is built to the ZRA Smart Invoice VSDC specification. Your business stays compliant without extra effort.',
    },
];

const trustBar = [
    { label: 'Location', value: 'Lusaka, Zambia' },
    { label: 'Compliance', value: 'ZRA Smart Invoice ready' },
    { label: 'Integrations', value: 'Airtel Money · MTN MoMo' },
    { label: 'Support', value: 'Local phone & WhatsApp support' },
];

const faqs = [
    { q: 'Which sectors do your products serve?', a: 'We build for Zambian schools (private and community), churches and ministries, SMEs requiring ZRA compliance, and NGOs running village banking or microfinance programmes. Every product is sector-specific, not a generic template adapted for the market.' },
    { q: 'Are your products ZRA Smart Invoice compliant?', a: 'Yes. Varidian BizManager is fully integrated with the ZRA Smart Invoice system via the VSDC API. All invoices are submitted to ZRA in real time, with ZRA receipt numbers and QR codes printed on every invoice.' },
    { q: 'Do the products support Airtel Money and MTN MoMo?', a: 'Yes. Our School Management System supports fee collection via Airtel Money and MTN MoMo. BizManager records mobile money payments against invoices. Village Banking supports mobile money disbursements.' },
    { q: 'What does local support mean in practice?', a: 'It means you call or WhatsApp a number in Lusaka and speak to someone who knows your product, your setup, and the Zambian context. No international support queues, no timezone issues.' },
    { q: 'Can I request a demo before committing?', a: 'Absolutely. WhatsApp us or fill in the contact form to request a demo of any product. We will walk you through the system and answer every question before you decide.' },
];

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add('in');
                }
            });
        },
        { threshold: 0.12 },
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));
});
</script>

<template>
    <SeoHead
        title="Varidian Consulting Limited — Software Built for Zambia"
        description="We build school, church, and business management systems purpose-built for Zambian organisations. ZRA Smart Invoice compliant. Locally supported in Lusaka."
        canonical-url="https://varidianlab.com"
    />

    <!-- ══════════════ HERO ══════════════ -->
    <section class="hero-bg relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-6 pt-24 pb-16 text-center">
        <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px); background-size: 60px 60px;"></div>

        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-pill mb-8 inline-flex">
                <span class="mkt-pill-dot"></span>
                Lusaka, Zambia — Management Systems for Zambian Organisations
            </div>

            <h1 class="hero-title mb-6">
                Software built for<br />
                <span class="accent">Zambian schools,<br />churches, and businesses</span>
            </h1>

            <p class="mx-auto mb-10 max-w-lg text-base leading-relaxed text-slate-400">
                We build management systems that understand the Zambian context — ECZ exams, ZRA Smart Invoice compliance, Airtel Money and MTN MoMo, NAPSA payroll. Built and supported right here in Lusaka.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                <a
                    href="https://wa.me/260971864421?text=Hi%2C%20I%27d%20like%20to%20request%20a%20demo%20of%20a%20Varidian%20product."
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mkt-btn-wa mkt-btn-wa-lg"
                >
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Request a demo
                </a>
                <a href="/products" class="mkt-btn-outline-hero">See our products</a>
            </div>

            <!-- Trust bar -->
            <div class="mt-14 flex flex-wrap items-center justify-center gap-8">
                <div v-for="t in trustBar" :key="t.label" class="text-center">
                    <div class="text-xs text-slate-500">{{ t.label }}</div>
                    <div class="mt-0.5 text-xs font-semibold text-slate-300">{{ t.value }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ PRODUCTS ══════════════ -->
    <section class="px-6 py-24" style="background:#080c10">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Products</div>
                <h2 class="mkt-sec-title">Management systems<br /><em>built for Zambia</em></h2>
                <p class="mx-auto mt-4 max-w-md text-sm text-slate-500">Each product is purpose-built for a specific Zambian sector — not adapted from a generic template.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2" data-reveal>
                <div v-for="product in products" :key="product.title" class="product-card">
                    <div class="mb-3 flex items-start gap-3">
                        <div class="mkt-service-icon flex-shrink-0" v-html="product.icon"></div>
                        <div>
                            <div v-if="product.badge" class="mb-1">
                                <span class="zra-badge">✓ {{ product.badge }}</span>
                            </div>
                            <h3 class="text-base font-semibold text-white">{{ product.title }}</h3>
                        </div>
                    </div>
                    <p class="mb-5 text-sm leading-relaxed text-slate-400">{{ product.desc }}</p>
                    <a :href="product.href" class="text-xs font-semibold text-teal-400 transition-colors hover:text-teal-300">Learn more →</a>
                </div>
            </div>

            <div class="mt-10 text-center" data-reveal>
                <a href="/products" class="mkt-btn-outline">View all products</a>
            </div>
        </div>
    </section>

    <!-- ══════════════ WHY VARIDIAN ══════════════ -->
    <section class="why-bg px-6 py-24">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Why Varidian</div>
                <h2 class="mkt-sec-title">What sets Varidian<br /><em>apart</em></h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="w in whyCards" :key="w.heading" class="why-card p-6" data-reveal>
                    <div class="mkt-service-icon mb-4" v-html="w.icon"></div>
                    <h4 class="mb-2 text-sm font-semibold text-white">{{ w.heading }}</h4>
                    <p class="text-xs leading-relaxed text-slate-400">{{ w.body }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ FAQ ══════════════ -->
    <section class="px-6 py-24" style="background:#0a0f14">
        <div class="mx-auto max-w-3xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">FAQ</div>
                <h2 class="mkt-sec-title">Common <em>questions</em></h2>
            </div>
            <div data-reveal>
                <div
                    v-for="(faq, i) in faqs"
                    :key="i"
                    class="faq-row cursor-pointer rounded-lg px-2 py-5"
                    @click="activeFaq = activeFaq === i ? null : i"
                >
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm font-medium text-white">{{ faq.q }}</span>
                        <div
                            class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full border border-white/10 transition-all"
                            :class="activeFaq === i ? 'bg-teal-500/20 border-teal-500/40' : ''"
                        >
                            <svg
                                class="h-3.5 w-3.5 transition-transform"
                                :class="activeFaq === i ? 'rotate-180 text-teal-400' : 'text-slate-500'"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                viewBox="0 0 24 24"
                            >
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div v-if="activeFaq === i" class="mt-3 pr-10 text-sm leading-relaxed text-slate-400">{{ faq.a }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ BOTTOM CTA ══════════════ -->
    <section class="px-6 py-24" style="background:#080c10">
        <div class="mx-auto max-w-4xl">
            <div class="mkt-cta-bottom px-8 py-16 text-center" data-reveal>
                <div class="mkt-chip mb-6">Let's get started</div>
                <h2 class="mkt-sec-title mb-4">
                    Ready to see a product<br />
                    <span style="background: linear-gradient(90deg,#00c9a7,#00a3ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">in action?</span>
                </h2>
                <p class="mx-auto mb-8 max-w-md text-sm leading-relaxed text-slate-400">Request a demo and we'll walk you through the product most relevant to your organisation. No obligation — just a practical conversation.</p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <WhatsAppButton
                        variant="inline"
                        label="Request a demo"
                        message="Hi, I'd like to request a demo of a Varidian product."
                        class="mkt-btn-wa-lg"
                    />
                    <a href="/products" class="mkt-btn-outline">See our products</a>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.hero-bg {
    background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(0, 180, 160, 0.18) 0%, transparent 65%),
        radial-gradient(ellipse 50% 40% at 80% 60%, rgba(0, 120, 255, 0.08) 0%, transparent 60%), #080c10;
}
.why-bg {
    background: radial-gradient(ellipse 60% 50% at 50% 50%, rgba(0, 180, 160, 0.07) 0%, transparent 70%), #0a0f14;
}
.hero-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(36px, 6vw, 72px);
    font-weight: 800;
    line-height: 1.08;
    color: #eef2f7;
}
.hero-title .accent {
    background: linear-gradient(90deg, #00c9a7, #00a3ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
.mkt-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(8px);
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 11px;
    color: #9ba8b8;
    letter-spacing: 0.04em;
}
.mkt-pill-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #00c9a7;
}
.mkt-btn-outline-hero {
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 999px;
    padding: 10px 26px;
    font-size: 14px;
    color: #c8d0db;
    background: rgba(255, 255, 255, 0.04);
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    text-decoration: none;
    display: inline-block;
}
.mkt-btn-outline-hero:hover {
    border-color: rgba(255, 255, 255, 0.35);
    background: rgba(255, 255, 255, 0.07);
}
.product-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 14px;
    padding: 28px 24px;
    transition: border-color 0.25s, background 0.25s, transform 0.25s;
}
.product-card:hover {
    border-color: rgba(0, 201, 167, 0.3);
    background: rgba(0, 201, 167, 0.04);
    transform: translateY(-3px);
}
.why-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 14px;
    overflow: hidden;
    transition: border-color 0.25s;
}
.why-card:hover {
    border-color: rgba(0, 163, 255, 0.3);
}
.faq-row {
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    transition: background 0.2s;
}
.faq-row:hover {
    background: rgba(255, 255, 255, 0.02);
}
.zra-badge {
    display: inline-block;
    background: rgba(0, 201, 167, 0.12);
    border: 1px solid rgba(0, 201, 167, 0.3);
    color: #00c9a7;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.05em;
    border-radius: 999px;
    padding: 2px 8px;
}
</style>
