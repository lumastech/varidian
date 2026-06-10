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
        title: 'MoE three-term calendar',
        desc: 'Academic year structured around the Zambian Ministry of Education three-term calendar. Term dates, holidays, and exam periods are built in.',
    },
    {
        title: 'ECZ exam tracking',
        desc: 'Track pupil performance in Grade 7, Grade 9, and Grade 12 national examinations. Division calculations and ECZ result entry built in.',
    },
    {
        title: 'Fee collection with mobile money',
        desc: 'Issue fee invoices and collect payments via Airtel Money or MTN MoMo. Automated receipts and arrears tracking. No more paper receipt books.',
    },
    {
        title: 'NAPSA & PAYE payroll',
        desc: 'Monthly payroll computation for teaching and non-teaching staff. NAPSA contributions and PAYE deductions calculated automatically.',
    },
    {
        title: 'Parent communication',
        desc: 'SMS notifications to parents for fee reminders, results, and school announcements.',
    },
    {
        title: 'Multi-school architecture',
        desc: 'One system for school groups or boards managing multiple campuses.',
    },
];
</script>

<template>
    <SeoHead
        title="Zambian School Management System (ZSSMS) — Varidian"
        description="School management system built for Zambian private and community schools. ECZ exam tracking, MoE three-term calendar, Airtel/MTN fee collection, NAPSA payroll. Supported in Lusaka."
        canonical-url="https://varidianlab.com/products/school-management-system"
    />

    <!-- PAGE HEADER -->
    <section class="mkt-page-header relative overflow-hidden px-6 pt-40 pb-20 text-center">
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px);background-size:60px 60px"></div>
        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-chip mb-5">School Management</div>
            <h1 class="mkt-page-title mb-5">Zambian School Management<br />System <span class="accent">(ZSSMS)</span></h1>
            <p class="mx-auto max-w-lg text-base leading-relaxed text-slate-400">
                Built for Zambian private and community schools. Every feature is designed around how Zambian schools actually operate.
            </p>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="px-6 py-20" style="background:#080c10">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Features</div>
                <h2 class="prod-sec-title">Built for how Zambian<br /><em>schools work</em></h2>
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
                    <p class="mb-2 text-2xl font-bold text-white">From K12,000/year</p>
                    <p class="mb-6 text-sm text-slate-400">Includes setup, onboarding, and local support. Contact us for a custom quote based on your school's size.</p>
                    <WhatsAppButton
                        variant="inline"
                        label="Request a ZSSMS demo"
                        message="Hi, I'd like to request a demo of the Zambian School Management System (ZSSMS)."
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
