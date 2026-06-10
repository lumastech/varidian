<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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

const products = [
    {
        title: 'Zambian School Management System (ZSSMS)',
        badge: null,
        desc: 'Grades 1–12 on the MoE three-term calendar. ECZ exam tracking, Airtel/MTN fee collection, NAPSA payroll. Built for Zambian private and community schools.',
        href: '/products/school-management-system',
        price: 'From K12,000/year',
        tags: ['ECZ Exams', 'Mobile Money', 'NAPSA Payroll', 'MoE Calendar'],
    },
    {
        title: 'Church Management System',
        badge: null,
        desc: 'Member and cell group registry. Tithe and offering tracking with mobile money. Attendance, events, and SMS or WhatsApp notifications for your congregation.',
        href: '/products/church-management-system',
        price: 'From K6,000/year',
        tags: ['Member Registry', 'Tithe Tracking', 'SMS/WhatsApp', 'Multi-campus'],
    },
    {
        title: 'Varidian BizManager',
        badge: 'ZRA Smart Invoice compliant',
        desc: 'A complete business manager for Zambian SMEs. Invoicing, inventory, sales, expenses, and reports — connected to ZRA Smart Invoice for full compliance.',
        href: '/products/bizmanager',
        price: 'From K2,400/year',
        tags: ['ZRA Smart Invoice', 'Inventory', 'Mobile Money', 'VAT Reports'],
    },
    {
        title: 'Village Banking & Microfinance Platform',
        badge: null,
        desc: 'Group and member management for VSLAs and microfinance organisations. Loan and savings cycle tracking, and mobile money disbursements.',
        href: '/products/village-banking',
        price: 'Custom pricing',
        tags: ['VSLA Groups', 'Loan Tracking', 'Mobile Money', 'Field Agents'],
    },
];
</script>

<template>
    <SeoHead
        title="Our Products — Varidian Consulting Limited"
        description="School management, church management, ZRA-compliant business management, and village banking systems — purpose-built for Zambian organisations."
        canonical-url="https://varidianlab.com/products"
    />

    <!-- PAGE HEADER -->
    <section class="mkt-page-header relative overflow-hidden px-6 pt-40 pb-20 text-center">
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px);background-size:60px 60px"></div>
        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-chip mb-5">Our Products</div>
            <h1 class="mkt-page-title mb-5">Management systems<br /><span class="accent">built for Zambia</span></h1>
            <p class="mx-auto max-w-lg text-base leading-relaxed text-slate-400">
                Each product is purpose-built for a specific Zambian sector — not adapted from a generic template. All products are web-based, mobile-friendly, and locally supported.
            </p>
        </div>
    </section>

    <!-- PRODUCTS GRID -->
    <section class="px-6 py-20" style="background:#080c10">
        <div class="mx-auto max-w-6xl">
            <div class="grid gap-6 sm:grid-cols-2" data-reveal>
                <div v-for="product in products" :key="product.title" class="prod-index-card flex flex-col">
                    <div class="mb-3">
                        <div v-if="product.badge" class="mb-2">
                            <span class="zra-badge">✓ {{ product.badge }}</span>
                        </div>
                        <h2 class="text-lg font-bold text-white">{{ product.title }}</h2>
                    </div>
                    <p class="mb-4 flex-1 text-sm leading-relaxed text-slate-400">{{ product.desc }}</p>
                    <div class="mb-5 flex flex-wrap gap-2">
                        <span v-for="tag in product.tags" :key="tag" class="prod-tag">{{ tag }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-white/5 pt-4">
                        <span class="text-xs font-semibold text-teal-400">{{ product.price }}</span>
                        <Link :href="product.href" class="text-xs font-semibold text-white transition-colors hover:text-teal-400">Learn more →</Link>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center" data-reveal>
                <div class="mkt-cta-bottom mx-auto max-w-2xl px-8 py-12">
                    <p class="mb-5 text-sm leading-relaxed text-slate-400">Not sure which product fits your organisation? WhatsApp us and we'll point you in the right direction.</p>
                    <WhatsAppButton
                        variant="inline"
                        label="Ask us which product fits"
                        message="Hi, I'd like to find out which Varidian product is right for my organisation."
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.prod-index-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 16px;
    padding: 28px;
    transition: border-color 0.25s, transform 0.25s;
}
.prod-index-card:hover {
    border-color: rgba(0, 201, 167, 0.3);
    transform: translateY(-3px);
}
.prod-tag {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 999px;
    padding: 2px 10px;
    font-size: 10px;
    font-weight: 500;
    color: #8898aa;
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
