<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

const activeFilter = ref('All');
const filters = ['All', 'Custom Software', 'Web & Mobile', 'Integration', 'Data & AI', 'Security & Audit'];

const projects = [
    {
        name: 'VillageBank Pro',
        industry: 'Financial Services',
        type: 'Custom Software',
        desc: 'A village banking and savings group management system for cooperative societies. Members track loans, savings, shares, and meeting schedules from any device.',
        outcome: '3,000+ members actively managed on the platform',
        tags: ['Vue.js', 'Node.js', 'PostgreSQL', 'Mobile-First'],
        bg: 'linear-gradient(135deg, rgba(0,201,167,0.08), rgba(0,0,0,0.6))',
        tagBg: 'rgba(0,201,167,0.12)', tagColor: '#00c9a7', tagBorder: 'rgba(0,201,167,0.3)',
        preview: `<svg viewBox="0 0 160 120" width="160" fill="none"><rect x="20" y="20" width="120" height="80" rx="8" stroke="#00c9a7" stroke-width="1"/><rect x="30" y="35" width="60" height="8" rx="2" fill="#00c9a7" opacity="0.4"/><rect x="30" y="50" width="40" height="6" rx="2" fill="#00c9a7" opacity="0.25"/><rect x="30" y="65" width="50" height="6" rx="2" fill="#00c9a7" opacity="0.25"/><rect x="30" y="80" width="35" height="6" rx="2" fill="#00c9a7" opacity="0.25"/><rect x="110" y="50" width="20" height="35" rx="3" fill="#00c9a7" opacity="0.3"/><rect x="85" y="62" width="20" height="23" rx="3" fill="#00c9a7" opacity="0.2"/></svg>`,
    },
    {
        name: 'MarketHub',
        industry: 'Retail & Commerce',
        type: 'Web & Mobile',
        desc: 'Multi-vendor e-commerce platform for local businesses to list, sell, and manage orders online. Integrated with mobile money payment providers.',
        outcome: '150+ vendors onboarded at launch',
        tags: ['React', 'Next.js', 'Airtel Money API', 'Stripe'],
        bg: 'linear-gradient(135deg, rgba(0,163,255,0.08), rgba(0,0,0,0.6))',
        tagBg: 'rgba(0,163,255,0.12)', tagColor: '#00a3ff', tagBorder: 'rgba(0,163,255,0.3)',
        preview: `<svg viewBox="0 0 160 120" width="160" fill="none"><rect x="10" y="10" width="140" height="100" rx="8" stroke="#00a3ff" stroke-width="1"/><rect x="20" y="20" width="55" height="38" rx="4" fill="#00a3ff" opacity="0.12"/><rect x="85" y="20" width="55" height="38" rx="4" fill="#00a3ff" opacity="0.12"/><rect x="20" y="68" width="55" height="32" rx="4" fill="#00a3ff" opacity="0.08"/><rect x="85" y="68" width="55" height="32" rx="4" fill="#00a3ff" opacity="0.08"/></svg>`,
    },
    {
        name: 'PayBridge',
        industry: 'Fintech',
        type: 'Integration',
        desc: "Middleware integration layer connecting a commercial bank's core banking system with Airtel Money, MTN Mobile Money, and Zamtel Kwacha networks.",
        outcome: '10,000+ transactions processed daily',
        tags: ['REST APIs', 'Java', 'PostgreSQL', 'OAuth 2.0'],
        bg: 'linear-gradient(135deg, rgba(147,51,234,0.08), rgba(0,0,0,0.6))',
        tagBg: 'rgba(147,51,234,0.12)', tagColor: '#a855f7', tagBorder: 'rgba(147,51,234,0.3)',
        preview: `<svg viewBox="0 0 160 120" width="160" fill="none"><circle cx="30" cy="60" r="18" stroke="#a855f7" stroke-width="1" fill="rgba(147,51,234,0.1)"/><circle cx="80" cy="60" r="14" stroke="#a855f7" stroke-width="1.5" fill="rgba(147,51,234,0.2)"/><circle cx="130" cy="60" r="18" stroke="#a855f7" stroke-width="1" fill="rgba(147,51,234,0.1)"/><line x1="48" y1="60" x2="66" y2="60" stroke="#a855f7" stroke-width="1.5" stroke-dasharray="3 2"/><line x1="94" y1="60" x2="112" y2="60" stroke="#a855f7" stroke-width="1.5" stroke-dasharray="3 2"/></svg>`,
    },
    {
        name: 'OperaERP',
        industry: 'Manufacturing',
        type: 'Custom Software',
        desc: 'Custom enterprise resource planning system covering inventory, procurement, HR, and financial reporting for a mid-sized manufacturing company.',
        outcome: '40% reduction in manual reporting time',
        tags: ['Laravel', 'Vue.js', 'MySQL', 'Docker'],
        bg: 'linear-gradient(135deg, rgba(245,158,11,0.08), rgba(0,0,0,0.6))',
        tagBg: 'rgba(245,158,11,0.12)', tagColor: '#f59e0b', tagBorder: 'rgba(245,158,11,0.3)',
        preview: `<svg viewBox="0 0 160 120" width="160" fill="none"><rect x="15" y="15" width="50" height="38" rx="5" stroke="#f59e0b" stroke-width="1" fill="rgba(245,158,11,0.08)"/><rect x="75" y="15" width="70" height="16" rx="3" fill="#f59e0b" opacity="0.15"/><rect x="75" y="38" width="70" height="16" rx="3" fill="#f59e0b" opacity="0.1"/><rect x="15" y="65" width="130" height="12" rx="3" fill="#f59e0b" opacity="0.12"/><rect x="15" y="85" width="130" height="12" rx="3" fill="#f59e0b" opacity="0.08"/></svg>`,
    },
    {
        name: 'InsightBoard',
        industry: 'Financial Services',
        type: 'Data & AI',
        desc: 'Real-time business intelligence dashboard pulling from multiple data sources into a unified executive reporting interface for a financial services firm.',
        outcome: 'Reporting cycle cut from 3 days to real-time',
        tags: ['Python', 'Power BI', 'PostgreSQL', 'ETL Pipelines'],
        bg: 'linear-gradient(135deg, rgba(16,185,129,0.08), rgba(0,0,0,0.6))',
        tagBg: 'rgba(16,185,129,0.12)', tagColor: '#10b981', tagBorder: 'rgba(16,185,129,0.3)',
        preview: `<svg viewBox="0 0 160 120" width="160" fill="none"><polyline points="15,90 40,60 65,72 95,35 125,48 150,20" stroke="#10b981" stroke-width="1.5" fill="none"/><circle cx="95" cy="35" r="4" fill="#10b981" opacity="0.7"/><rect x="15" y="95" width="130" height="1" fill="#10b981" opacity="0.15"/><rect x="20" y="75" width="8" height="20" rx="1" fill="#10b981" opacity="0.2"/><rect x="50" y="55" width="8" height="40" rx="1" fill="#10b981" opacity="0.2"/><rect x="80" y="40" width="8" height="55" rx="1" fill="#10b981" opacity="0.25"/></svg>`,
    },
    {
        name: 'SecureBase Audit',
        industry: 'Public Sector',
        type: 'Security & Audit',
        desc: 'Comprehensive ICT security audit and remediation for a parastatal organisation, covering network infrastructure, access controls, and disaster recovery planning.',
        outcome: 'All critical vulnerabilities identified and resolved',
        tags: ['Penetration Testing', 'Risk Assessment', 'ISO 27001', 'DR Planning'],
        bg: 'linear-gradient(135deg, rgba(239,68,68,0.08), rgba(0,0,0,0.6))',
        tagBg: 'rgba(239,68,68,0.12)', tagColor: '#ef4444', tagBorder: 'rgba(239,68,68,0.3)',
        preview: `<svg viewBox="0 0 160 120" width="160" fill="none"><path d="M80 15 L130 35 L130 75 C130 95 80 110 80 110 C80 110 30 95 30 75 L30 35 Z" stroke="#ef4444" stroke-width="1" fill="rgba(239,68,68,0.08)"/><path d="M65 63 l10 10 20-22" stroke="#ef4444" stroke-width="2" fill="none" stroke-linecap="round"/></svg>`,
    },
];

const filteredProjects = computed(() => {
    if (activeFilter.value === 'All') { return projects; }
    return projects.filter((p) => p.type === activeFilter.value);
});

const industries = [
    'Financial Services', 'Fintech & Payments', 'Retail & E-Commerce',
    'Manufacturing', 'Public Sector', 'Village Banking', 'Healthcare', 'Education',
];

onMounted(() => {
    const obs = new IntersectionObserver(
        (entries) => entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); } }),
        { threshold: 0.08 },
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => obs.observe(el));
});
</script>

<template>
    <Head title="Our Work — Varidian" />

    <!-- PAGE HEADER -->
    <section class="mkt-page-header relative overflow-hidden px-6 pt-40 pb-20 text-center" style="background: radial-gradient(ellipse 80% 50% at 50% -5%, rgba(0,163,255,0.12) 0%, transparent 65%), #080c10;">
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px);background-size:60px 60px"></div>
        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-chip mb-5">Our Work</div>
            <h1 class="mkt-page-title mb-5">Software Built for<br /><span class="accent">Real Businesses</span></h1>
            <p class="mx-auto max-w-lg text-base leading-relaxed text-slate-400">A selection of projects across industries — each one custom-built to solve a specific business problem, on time and within budget.</p>
        </div>
    </section>

    <!-- PROJECTS -->
    <section class="px-6 py-20" style="background:#080c10">
        <div class="mx-auto max-w-6xl">

            <!-- Filter buttons -->
            <div class="mb-12 flex flex-wrap justify-center gap-2" data-reveal>
                <button
                    v-for="f in filters"
                    :key="f"
                    class="work-filter-btn"
                    :class="activeFilter === f ? 'active' : ''"
                    @click="activeFilter = f"
                >
                    {{ f }}
                </button>
            </div>

            <!-- Project grid -->
            <div class="mb-16 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="p in filteredProjects" :key="p.name" class="work-project-card" data-reveal>
                    <div class="work-project-preview relative flex h-44 items-center justify-center" :style="`background: ${p.bg}`">
                        <div v-html="p.preview" class="opacity-60"></div>
                        <div class="absolute top-4 left-4">
                            <span class="work-industry-tag" :style="`background:${p.tagBg};color:${p.tagColor};border:1px solid ${p.tagBorder}`">{{ p.industry }}</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-2 flex items-start justify-between gap-2">
                            <h3 class="text-base font-bold text-white">{{ p.name }}</h3>
                            <span class="mkt-chip flex-shrink-0">{{ p.type }}</span>
                        </div>
                        <p class="mb-4 text-sm leading-relaxed text-slate-400">{{ p.desc }}</p>
                        <div class="mkt-sep mb-4"></div>
                        <div class="mb-4 flex items-center gap-2">
                            <svg class="h-4 w-4 flex-shrink-0 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span class="text-xs font-semibold text-teal-400">{{ p.outcome }}</span>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <span v-for="tag in p.tags" :key="tag" class="rounded-full border border-white/10 px-2.5 py-0.5 text-xs text-slate-500">{{ tag }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Industries served -->
            <div data-reveal>
                <h3 class="mb-8 text-center text-xs font-semibold tracking-widest text-slate-500 uppercase">Industries We've Served</h3>
                <div class="mb-16 flex flex-wrap justify-center gap-3">
                    <span
                        v-for="ind in industries"
                        :key="ind"
                        class="rounded-full border px-5 py-2 text-sm text-slate-400"
                        style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.08)"
                    >
                        {{ ind }}
                    </span>
                </div>
            </div>

            <div class="mkt-sep mb-16"></div>

            <!-- CTA -->
            <div class="mkt-cta-bottom px-8 py-14 text-center" data-reveal>
                <div class="mkt-chip mb-5">Start Your Project</div>
                <h2 class="work-sec-title mb-3">Let's Build Something Together</h2>
                <p class="mx-auto mb-8 max-w-md text-sm leading-relaxed text-slate-400">Every project starts with a conversation. Book a free discovery call and tell us about your business.</p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="https://wa.me/260971864421" target="_blank" rel="noopener noreferrer" class="mkt-btn-wa mkt-btn-wa-lg">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        Book Free Discovery Call
                    </a>
                    <a href="/services" class="mkt-btn-outline">View Our Services</a>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.work-sec-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(22px, 3vw, 36px);
    font-weight: 800;
    color: #eef2f7;
    line-height: 1.2;
}
.work-project-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 16px;
    overflow: hidden;
    transition: border-color 0.25s, transform 0.25s;
}
.work-project-card:hover {
    border-color: rgba(0, 201, 167, 0.3);
    transform: translateY(-3px);
}
.work-project-preview {
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}
.work-filter-btn {
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 999px;
    padding: 6px 16px;
    font-size: 12px;
    color: #8898aa;
    background: transparent;
    cursor: pointer;
    transition: all 0.2s;
}
.work-filter-btn.active,
.work-filter-btn:hover {
    background: rgba(0, 201, 167, 0.1);
    border-color: rgba(0, 201, 167, 0.3);
    color: #00c9a7;
}
.work-industry-tag {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    border-radius: 999px;
    padding: 3px 10px;
}
</style>
