<script setup lang="ts">
import MarketingCtaBand from '@/components/marketing/MarketingCtaBand.vue';
import MarketingPageHero from '@/components/marketing/MarketingPageHero.vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

interface Product {
    name: string;
    sector: string;
    summary: string;
    features: string[];
    demoLabel: string;
    detailHref: string | null;
}

const products: Product[] = [
    {
        name: 'SKUU',
        sector: 'Schools',
        summary: 'School and student management for private, mission and grant-aided schools, Grades 1–12.',
        features: ['Enrolment, classes & timetables', 'Fees collected by Airtel Money & MTN MoMo', 'ECZ exam tracking at Grades 7, 9 & 12', 'Payroll with NAPSA & PAYE, parent SMS'],
        demoLabel: 'Request a SKUU demo',
        detailHref: '/products/school-management-system',
    },
    {
        name: 'Varidian Reach',
        sector: 'NGOs',
        summary: 'NGO management in a dedicated installation for each organisation — your data never shares a database.',
        features: ['Members & beneficiaries', 'Programmes & activities', 'Donors, grants & finances', 'Donor-ready reporting'],
        demoLabel: 'Request a Reach demo',
        detailHref: null,
    },
    {
        name: 'BizManager',
        sector: 'SMEs',
        summary: 'Everyday business management for SMEs with ZRA Smart Invoice built in.',
        features: ['Sales, quotations & invoices', 'Stock & suppliers', 'Customer records', 'ZRA-compliant invoicing'],
        demoLabel: 'Request a BizManager demo',
        detailHref: '/products/bizmanager',
    },
    {
        name: 'Varidian Books',
        sector: 'Accounting',
        summary: "Offline-first accounting that keeps working without internet and syncs when you're back online.",
        features: ['Ledgers, invoices & bills', 'Bank reconciliation', 'Financial statements', 'Works offline, syncs to the cloud'],
        demoLabel: 'Join the waiting list',
        detailHref: null,
    },
    {
        name: 'Village Banking',
        sector: 'Microfinance',
        summary: 'A platform for savings groups and microfinance institutions.',
        features: ['Member savings & shares', 'Loan applications & schedules', 'Repayments by mobile money', 'Group & portfolio reports'],
        demoLabel: 'Request a demo',
        detailHref: '/products/village-banking',
    },
    {
        name: 'ChurchMS',
        sector: 'Churches',
        summary: 'Church management for congregations of any size.',
        features: ['Membership & families', 'Tithes, offerings & pledges', 'Groups, events & attendance', 'SMS announcements'],
        demoLabel: 'Request a demo',
        detailHref: '/products/church-management-system',
    },
    {
        name: 'Coursify',
        sector: 'Higher education',
        summary: 'Online learning management for universities and colleges.',
        features: ['Courses & learning materials', 'Assignments & online assessments', 'Student progress tracking', 'Lecturer & admin dashboards'],
        demoLabel: 'Request a demo',
        detailHref: null,
    },
    {
        name: 'Varidian Events',
        sector: 'Events',
        summary: 'Event registration and ticketing — a privacy-conscious replacement for generic online forms.',
        features: ['Custom registration forms', 'Online payments', 'Attendee lists & check-in', 'Consent captured for every registrant'],
        demoLabel: 'Request a demo',
        detailHref: null,
    },
];

const buyingOptions = [
    {
        name: 'Cloud',
        terms: 'Setup fee + monthly fee',
        features: ['Hosted by Varidian in Zambia', 'Updates & backups included', 'Standard support'],
        featured: false,
    },
    {
        name: 'Cloud + IT support',
        terms: 'Setup fee + higher monthly fee',
        features: ['Everything in Cloud', 'Day-to-day IT help for your team', 'Priority response'],
        featured: true,
    },
    {
        name: 'Own it',
        terms: 'One-off licence',
        features: ['Installed on your infrastructure', 'You own the system & customisations', 'Optional support agreement (SLA)'],
        featured: false,
    },
];

function demoMessage(product: Product): string {
    return product.demoLabel === 'Join the waiting list'
        ? `Hi, I'd like to join the waiting list for ${product.name}.`
        : `Hi, I'd like to request a demo of ${product.name}.`;
}

useScrollReveal();
</script>

<template>
    <SeoHead
        title="Products — Ready-made platforms for Zambian organisations | Varidian"
        description="SKUU, Varidian Reach, BizManager, Varidian Books, Village Banking, ChurchMS, Coursify and Varidian Events — configured to your organisation, branded for you and supported locally."
        canonical-url="https://varidianlab.com/products"
    />

    <div class="v-landing">
        <MarketingPageHero
            eyebrow="Products"
            title="Ready-made platforms for Zambian organisations."
            lead="Each product is configured to your organisation, branded for you and supported locally. Run it on our cloud or own it outright."
        />

        <!-- ══════════════ PRODUCT CATALOGUE ══════════════ -->
        <section class="v-sec">
            <div class="v-wrap">
                <div class="v-prod-grid v-prod-grid--wide" data-reveal>
                    <article v-for="product in products" :key="product.name" class="v-prod">
                        <div class="v-prod-head">
                            <h2 class="v-prod-name">{{ product.name }}</h2>
                            <div class="v-prod-sector">{{ product.sector }}</div>
                        </div>
                        <p>{{ product.summary }}</p>
                        <ul class="v-checklist v-checklist--single">
                            <li v-for="feature in product.features" :key="feature">{{ feature }}</li>
                        </ul>
                        <div class="v-prod-links">
                            <WhatsAppButton variant="inline" :label="`${product.demoLabel} →`" :message="demoMessage(product)" class="v-prod-demo" />
                            <a v-if="product.detailHref" :href="product.detailHref">Learn more</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- ══════════════ HOW YOU CAN BUY ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">How you can buy</div>
                    <h2 class="v-sec-title">Three ways to run <em>any Varidian product.</em></h2>
                </div>
                <div class="v-tiers" data-reveal>
                    <div v-for="option in buyingOptions" :key="option.name" class="v-tier" :class="{ 'v-tier--featured': option.featured }">
                        <div>
                            <h3>{{ option.name }}</h3>
                            <div class="v-tier-term">{{ option.terms }}</div>
                        </div>
                        <ul class="v-checklist v-checklist--single">
                            <li v-for="feature in option.features" :key="feature">{{ feature }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <MarketingCtaBand title="See it working with your own data." lead="Book a free demo and we'll walk your team through it.">
            <WhatsAppButton variant="inline" label="Book a demo" message="Hi, I'd like to book a demo of a Varidian product." class="v-btn-wa" />
        </MarketingCtaBand>
    </div>
</template>
