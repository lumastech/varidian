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
        title: 'Village group management',
        desc: 'Register and manage VSLA groups, savings clubs, and village banking groups with full member profiles.',
    },
    {
        title: 'Loan & savings cycles',
        desc: 'Track savings cycle rounds, loan applications, disbursements, and repayments per member and group.',
    },
    {
        title: 'Campaign & referral tracking',
        desc: 'Manage outreach campaigns and track new group registrations from field agents.',
    },
    {
        title: 'Affiliation & hierarchy',
        desc: 'Support for national organisations managing regional, district, and local-level structures.',
    },
    {
        title: 'Mobile money disbursements',
        desc: 'Disburse loans and savings payouts to members via Airtel Money or MTN MoMo.',
    },
    {
        title: 'Field agent portal',
        desc: 'Field agents can register groups and members from their mobile browser.',
    },
];
</script>

<template>
    <SeoHead
        title="Village Banking & Microfinance Platform — Varidian"
        description="Village banking and VSLA management platform for Zambian NGOs and microfinance organisations. Group management, loan tracking, savings cycles, and mobile money disbursements."
        canonical-url="https://varidianlab.com/products/village-banking"
    />

    <!-- PAGE HEADER -->
    <section class="mkt-page-header relative overflow-hidden px-6 pt-40 pb-20 text-center">
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px);background-size:60px 60px"></div>
        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-chip mb-5">Village Banking</div>
            <h1 class="mkt-page-title mb-5">Village Banking &<br /><span class="accent">Microfinance Platform</span></h1>
            <p class="mx-auto max-w-lg text-base leading-relaxed text-slate-400">
                Built for Zambian NGOs and community finance groups. Manage VSLAs, savings cycles, loans, and mobile money disbursements from one platform.
            </p>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="px-6 py-20" style="background:#080c10">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Features</div>
                <h2 class="prod-sec-title">Built for community<br /><em>finance in Zambia</em></h2>
            </div>

            <div class="mb-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-reveal>
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
                    <p class="mb-2 text-2xl font-bold text-white">Custom pricing</p>
                    <p class="mb-6 text-sm text-slate-400">Pricing is based on organisation size and number of groups. Contact us to discuss your requirements.</p>
                    <WhatsAppButton
                        variant="inline"
                        label="Get in touch"
                        message="Hi, I'd like to learn more about the Village Banking platform."
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
    color: #eef2f7;
    line-height: 1.15;
}
.prod-sec-title em {
    font-style: normal;
    color: #8898aa;
}
.feature-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 14px;
    padding: 24px;
    transition: border-color 0.25s;
}
.feature-card:hover {
    border-color: rgba(0, 201, 167, 0.25);
}
.pricing-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(0, 201, 167, 0.2);
    border-radius: 16px;
    padding: 40px 32px;
}
</style>
