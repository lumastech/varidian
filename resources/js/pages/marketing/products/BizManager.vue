<script setup lang="ts">
import { onMounted } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';

onMounted(() => {
    const obs = new IntersectionObserver(
        (entries) => entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); } }),
        { threshold: 0.08 },
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => obs.observe(el));
});

const features = [
    {
        title: 'ZRA Smart Invoice integration',
        desc: 'All invoices submitted to ZRA\'s Smart Invoice system in real time via the VSDC API. ZRA receipt number and QR code printed on every invoice.',
    },
    {
        title: 'Offline-first operation',
        desc: 'The system works without internet. Transactions, inventory changes, and sales are captured locally and synced to ZRA and the cloud automatically when connectivity returns.',
    },
    {
        title: 'Inventory management',
        desc: 'Stock levels, goods received notes, reorder alerts, and stock movement history.',
    },
    {
        title: 'Sales & POS',
        desc: 'Fast counter sales interface with barcode support. Cash and mobile money payment recording.',
    },
    {
        title: 'Mobile money payments',
        desc: 'Record Airtel Money and MTN MoMo payments against invoices. Payment request and reconciliation.',
    },
    {
        title: 'Expenses tracking',
        desc: 'Record business expenses by category with receipt uploads.',
    },
    {
        title: 'VAT & financial reports',
        desc: 'VAT summary, outstanding invoices, profit overview, and payment method breakdowns. Exportable to PDF.',
    },
    {
        title: 'Multi-branch support',
        desc: 'Manage multiple business locations from one dashboard.',
    },
];
</script>

<template>
    <SeoHead
        title="Varidian BizManager — ZRA Smart Invoice–Compliant Business Manager"
        description="ZRA Smart Invoice–compliant business management system for Zambian SMEs. Invoicing, inventory, sales, expenses, mobile money payments, and VAT reports."
        canonical-url="https://varidianlab.com/products/bizmanager"
    />

    <!-- PAGE HEADER -->
    <section class="mkt-page-header relative overflow-hidden px-6 pt-40 pb-20 text-center">
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(var(--mkt-dot-grid) 1px,transparent 1px),linear-gradient(90deg,var(--mkt-dot-grid) 1px,transparent 1px);background-size:60px 60px"></div>
        <div class="relative z-10 mx-auto max-w-3xl">
            <!-- ZRA compliance badge -->
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-teal-500/30 bg-teal-500/10 px-4 py-2 text-sm font-semibold text-teal-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                ZRA Smart Invoice compliant — VSDC integrated
            </div>
            <h1 class="mkt-page-title mb-5">Varidian<br /><span class="accent">BizManager</span></h1>
            <p class="mx-auto max-w-lg text-base leading-relaxed text-slate-400">
                A complete business management system for Zambian SMEs, fully integrated with ZRA Smart Invoice. Works on mobile. Supported locally.
            </p>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="mkt-sec-dark px-6 py-20">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Features</div>
                <h2 class="prod-sec-title">Everything a Zambian business<br /><em>needs to stay compliant</em></h2>
            </div>

            <div class="mb-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-4" data-reveal>
                <div v-for="feature in features" :key="feature.title" class="feature-card">
                    <div class="mb-3 flex h-8 w-8 items-center justify-center rounded-lg" style="background:rgba(0,201,167,0.1);border:1px solid rgba(0,201,167,0.2)">
                        <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-sm font-semibold text-white">{{ feature.title }}</h3>
                    <p class="text-xs leading-relaxed text-slate-400">{{ feature.desc }}</p>
                </div>
            </div>

            <!-- Pricing -->
            <div class="mkt-sep mb-16"></div>
            <div class="mb-16 mx-auto max-w-2xl" data-reveal>
                <div class="pricing-card text-center">
                    <div class="mkt-chip mb-4">Pricing</div>
                    <p class="mb-1 text-2xl font-bold text-white">From K2,400/year</p>
                    <p class="mb-1 text-sm text-slate-500">K200/month equivalent</p>
                    <p class="mb-6 text-sm text-slate-400">30-day free trial — no credit card required.</p>
                    <WhatsAppButton
                        variant="inline"
                        label="Start free trial"
                        message="Hi, I'd like to start a free trial of Varidian BizManager."
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.prod-sec-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(24px, 3.5vw, 42px);
    font-weight: 800;
    color: var(--mkt-text-h);
    line-height: 1.15;
}
.prod-sec-title em {
    font-style: normal;
    color: var(--mkt-text-m);
}
.feature-card {
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 14px;
    padding: 24px;
    transition: border-color 0.25s;
}
.feature-card:hover {
    border-color: rgba(0, 201, 167, 0.25);
}
.pricing-card {
    background: var(--mkt-surface);
    border: 1px solid rgba(0, 201, 167, 0.2);
    border-radius: 16px;
    padding: 40px 32px;
}
</style>
