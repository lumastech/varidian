<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const coreSubs = [
    { name: 'Custom Business Applications', desc: 'Tailored software that automates and streamlines your core business processes.' },
    { name: 'Web Applications & Portals', desc: 'Responsive, high-performance web platforms optimised for all devices.' },
    { name: 'Mobile Applications', desc: 'Native and cross-platform apps for Android and iOS.' },
    { name: 'Enterprise Software', desc: 'Robust back-office systems for complex operational requirements.' },
    { name: 'Cloud-Based Solutions', desc: 'Scalable, always-available applications built on modern cloud infrastructure.' },
    { name: 'API & Payment Integration', desc: 'Bank APIs, mobile money (Airtel, MTN, Zamtel), and third-party system connections.' },
];

const services = [
    {
        title: 'Web Development',
        desc: 'Your digital presence, professionally crafted. We design and develop experiences that are visually compelling and functionally powerful.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>`,
        bullets: ['Corporate websites & landing pages', 'E-commerce platforms & online stores', 'Web portals & information systems', 'CMS development', 'Hosting support & optimisation'],
    },
    {
        title: 'Cybersecurity',
        desc: 'Cyber threats are increasingly sophisticated. We help you identify vulnerabilities, mitigate risks, and protect your most valuable digital assets.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>`,
        bullets: ['Vulnerability assessments & penetration testing', 'Risk management & cybersecurity strategy', 'Security policy & staff awareness training', 'Incident response planning'],
    },
    {
        title: 'Data Analytics & AI',
        desc: 'Transform raw data into actionable insights that enable smarter, faster decision-making across your organisation.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>`,
        bullets: ['BI dashboards & reporting', 'Data pipeline design & management', 'Operational analytics & monitoring', 'Custom data models & AI automation'],
    },
    {
        title: 'Payment & Financial Integration',
        desc: 'Bridge the gap between technology and finance with solutions that support your financial operations and open new growth opportunities.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>`,
        bullets: ['Bank API & core banking integration', 'Mobile money (Airtel, MTN, Zamtel)', 'Order financing technology', 'Financial monitoring & reporting dashboards'],
    },
    {
        title: 'ICT Audits',
        desc: 'Independent, thorough assessments of your technology environment — giving you the clarity and confidence to act.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>`,
        bullets: ['Systems & cybersecurity audits', 'Disaster recovery readiness review', 'Business process & technology alignment', 'Database integrity & compliance audit'],
    },
    {
        title: 'Management Consulting',
        desc: 'Technology is most powerful when aligned with clear business strategy. We help you define where you are going and build the foundations to get there.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>`,
        bullets: ['Digitalising operations & process mapping', 'Project management', 'Digital strategy roadmaps', 'IT advisory & vendor selection'],
    },
];

const addServices = [
    {
        title: 'ICT Outsourcing',
        desc: 'Full or partial management of your IT operations by our experienced team — skilled capacity when and where you need it.',
        icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>`,
    },
    {
        title: 'Backup & Security Management',
        desc: 'Proactive management of your data backups and security systems, including off-site backup solutions.',
        icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>`,
    },
    {
        title: 'Data Entry & Collection',
        desc: 'Accurate, efficient data capture and management services to keep your records clean and reliable.',
        icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>`,
    },
    {
        title: 'ICT Equipment Supply',
        desc: 'Premium hardware and licensed software from leading vendors — competitively priced with reliable after-sales support.',
        icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>`,
    },
];

onMounted(() => {
    const obs = new IntersectionObserver(
        (entries) => entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); } }),
        { threshold: 0.1 },
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => obs.observe(el));
});
</script>

<template>
    <Head title="Services — Varidian" />

    <!-- PAGE HEADER -->
    <section class="mkt-page-header relative overflow-hidden px-6 pt-40 pb-20 text-center" style="background: radial-gradient(ellipse 80% 50% at 50% -5%, rgba(0,180,160,0.14) 0%, transparent 65%), #080c10;">
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px);background-size:60px 60px"></div>
        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-chip mb-5">Our Services</div>
            <h1 class="mkt-page-title mb-5">Everything You Need to<br /><span class="accent">Build, Secure &amp; Scale</span></h1>
            <p class="mx-auto max-w-lg text-base leading-relaxed text-slate-400">Software development is our core. Every other service we offer is designed to support, protect, and extend the technology that runs your business.</p>
        </div>
    </section>

    <!-- CORE SERVICE & GRID -->
    <section class="px-6 py-20" style="background:#080c10">
        <div class="mx-auto max-w-6xl">

            <!-- Core service featured block -->
            <div class="mb-5" data-reveal>
                <div class="rounded-2xl p-8" style="background:linear-gradient(160deg,rgba(0,201,167,0.09),rgba(0,163,255,0.05));border:1px solid rgba(0,201,167,0.28)">
                    <div class="mb-6 flex flex-col gap-5 sm:flex-row sm:items-start">
                        <div class="mkt-service-icon flex-shrink-0" style="width:52px;height:52px;border-radius:12px;">
                            <svg class="h-6 w-6 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                        <div>
                            <div class="mkt-chip mb-2">Core Service</div>
                            <h2 class="svc-sec-title mb-2">Custom Software Development</h2>
                            <p class="max-w-3xl text-sm leading-relaxed text-slate-400">
                                Our flagship practice. We cover the entire development lifecycle — from requirements discovery and system design through to testing, deployment, and ongoing support. We specialise in building custom information systems that are scalable, reliable, and secure. Every solution is engineered to your exact business context, not adapted from a generic template.
                            </p>
                        </div>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div v-for="sub in coreSubs" :key="sub.name" class="svc-sub-item">
                            <svg class="svc-check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                            <div>
                                <div class="mb-0.5 text-xs font-semibold text-white">{{ sub.name }}</div>
                                <div class="text-xs leading-relaxed text-slate-500">{{ sub.desc }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supporting services grid -->
            <div class="mb-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="svc in services" :key="svc.title" class="mkt-card p-6" data-reveal>
                    <div class="mkt-service-icon mb-4" v-html="svc.icon"></div>
                    <h3 class="mb-2 text-base font-semibold text-white">{{ svc.title }}</h3>
                    <p class="mb-4 text-sm leading-relaxed text-slate-400">{{ svc.desc }}</p>
                    <ul class="space-y-2">
                        <li v-for="b in svc.bullets" :key="b" class="flex items-start gap-2 text-xs text-slate-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 flex-shrink-0 text-teal-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                            {{ b }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Additional services -->
            <div class="mb-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-4" data-reveal>
                <div v-for="add in addServices" :key="add.title" class="mkt-card p-5">
                    <div class="mb-3 text-teal-400" v-html="add.icon"></div>
                    <h4 class="mb-1.5 text-sm font-semibold text-white">{{ add.title }}</h4>
                    <p class="text-xs leading-relaxed text-slate-500">{{ add.desc }}</p>
                </div>
            </div>

            <div class="mkt-sep mb-16"></div>

            <!-- CTA -->
            <div class="mkt-cta-bottom px-8 py-14 text-center" data-reveal>
                <div class="mkt-chip mb-5">Work With Us</div>
                <h2 class="svc-sec-title mb-3">Have a Project in Mind?</h2>
                <p class="mx-auto mb-8 max-w-md text-sm leading-relaxed text-slate-400">Start with a free 45-minute discovery call. We'll learn about your business and tell you exactly how we can help — no obligation.</p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="https://wa.me/260971864421" target="_blank" rel="noopener noreferrer" class="mkt-btn-teal">Book Free Discovery Call</a>
                    <a href="/contact" class="mkt-btn-outline">Send Us a Message</a>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.svc-sec-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(22px, 3vw, 36px);
    font-weight: 800;
    color: #eef2f7;
    line-height: 1.2;
}
.svc-sub-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 10px;
}
.svc-sub-item:hover {
    border-color: rgba(0, 201, 167, 0.2);
}
.svc-check {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    color: #00c9a7;
    margin-top: 1px;
}
</style>
