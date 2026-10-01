<script setup lang="ts">
import MarketingCtaBand from '@/components/marketing/MarketingCtaBand.vue';
import MarketingPageHero from '@/components/marketing/MarketingPageHero.vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

const values = [
    { title: 'Local first', body: 'We design around Zambian payments, regulations, connectivity and calendars before anything else.' },
    { title: 'Plain dealing', body: 'Clear scopes, fixed quotations and honest timelines — no surprises on the invoice.' },
    {
        title: 'Data responsibility',
        body: 'We treat client data as theirs: stored in Zambia, protected, and handled in line with the Data Protection Act, 2021.',
    },
    { title: 'In it for the long run', body: 'Go-live is the start. We train your people, support the system and keep improving it.' },
];

const leaders = [
    {
        name: 'Lubomba Mulomya',
        initials: 'LM',
        role: 'Director & Software Development Lead',
        bio: "A full-stack developer and systems engineer, Lubomba leads Varidian's product development and infrastructure — from application architecture to the servers that host client systems.",
    },
    {
        name: 'Banji Chisumpa',
        initials: 'BC',
        role: 'Executive Chairperson',
        bio: "Provides strategic leadership and oversight for Varidian's operations, partnerships, and growth across African markets.",
    },
];

const capabilities = [
    { label: 'Applications', value: 'Laravel, Vue 3, TypeScript, Tailwind CSS, MySQL' },
    { label: 'Integrations', value: 'Airtel Money, MTN MoMo, ZRA Smart Invoice, bulk SMS gateways' },
    { label: 'Infrastructure', value: 'Enterprise servers in Lusaka, virtualised and backed up, with encrypted connections end to end' },
    { label: 'Quality', value: 'Automated testing, role-based access control and full audit logs as standard' },
];

const companyFacts = [
    { label: 'Registered name', value: 'Varidian Consulting Limited' },
    { label: 'PACRA reg. no.', value: '120261044865' },
    { label: 'TPIN', value: '2004412831' },
    { label: 'Head office', value: 'Lusaka, Zambia' },
];

useScrollReveal();
</script>

<template>
    <SeoHead
        title="About Varidian — A Zambian software house"
        description="Varidian Consulting Limited is a Lusaka-based software development and consulting firm. We design, build, host and support business systems for schools, NGOs, churches, financial groups and growing businesses."
        canonical-url="https://varidianlab.com/about"
    />

    <div class="v-landing">
        <MarketingPageHero
            eyebrow="About Varidian"
            title="A Zambian software house building the systems Zambian organisations actually need."
            lead="Varidian Consulting Limited is a Lusaka-based software development and consulting firm. We design, build, host and support business systems for schools, NGOs, churches, financial groups and growing businesses."
        />

        <!-- ══════════════ STORY ══════════════ -->
        <section class="v-sec">
            <div class="v-wrap v-split" data-reveal>
                <div>
                    <div class="v-chip">Our story</div>
                    <h2 class="v-sec-title">We started by fixing the gap between <em>global software and local reality.</em></h2>
                </div>
                <div class="v-prose">
                    <p>
                        Too many Zambian organisations were running on spreadsheets, paper registers or imported software that didn't understand mobile money, ZRA requirements or the school calendar. Varidian was founded to close that gap.
                    </p>
                    <p>
                        We began with custom builds for individual clients — a management system for a women's development association, a village banking platform for a microfinance group. Each project taught us what local organisations need, and those lessons became our product range.
                    </p>
                    <p>
                        Today we combine custom development, ready-made platforms and our own Zambian hosting infrastructure, so a client can go from idea to a running, supported system with one local partner.
                    </p>
                </div>
            </div>
        </section>

        <!-- ══════════════ MISSION / VISION ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap v-pair" data-reveal>
                <div class="v-panel">
                    <div class="v-eyebrow">Mission</div>
                    <p>To give Zambian organisations reliable, locally-built software they can afford, understand and own.</p>
                </div>
                <div class="v-panel">
                    <div class="v-eyebrow">Vision</div>
                    <p>A Zambia where every school, NGO and business runs on technology built for its realities — and hosted at home.</p>
                </div>
            </div>
        </section>

        <!-- ══════════════ VALUES ══════════════ -->
        <section class="v-sec">
            <div class="v-wrap v-why" data-reveal>
                <div class="v-why-head">
                    <div class="v-chip">What we believe</div>
                    <h2 class="v-sec-title">How we work <em>with every client.</em></h2>
                </div>
                <ol class="v-why-list">
                    <li v-for="(value, i) in values" :key="value.title" class="v-why-row">
                        <span class="v-why-n">{{ String(i + 1).padStart(2, '0') }}</span>
                        <div>
                            <h3>{{ value.title }}</h3>
                            <p>{{ value.body }}</p>
                        </div>
                    </li>
                </ol>
            </div>
        </section>

        <!-- ══════════════ LEADERSHIP ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Leadership</div>
                    <h2 class="v-sec-title">The people <em>behind Varidian.</em></h2>
                </div>
                <div class="v-people" data-reveal>
                    <article v-for="leader in leaders" :key="leader.name" class="v-panel v-person">
                        <div class="v-person-avatar" aria-hidden="true">{{ leader.initials }}</div>
                        <div>
                            <h3>{{ leader.name }}</h3>
                            <div class="v-person-role">{{ leader.role }}</div>
                            <p>{{ leader.bio }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- ══════════════ CAPABILITIES ══════════════ -->
        <section class="v-sec">
            <div class="v-wrap v-split" data-reveal>
                <div>
                    <div class="v-chip">How we build</div>
                    <h2 class="v-sec-title">Modern tools. <em>Our own infrastructure.</em></h2>
                    <p class="v-sec-lead">We build on proven, open-source technology — no lock-in to expensive licences — and run client systems on servers we own and operate in Zambia.</p>
                </div>
                <dl class="v-rows">
                    <div v-for="capability in capabilities" :key="capability.label" class="v-row">
                        <dt>{{ capability.label }}</dt>
                        <dd>{{ capability.value }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- ══════════════ COMPANY FACTS ══════════════ -->
        <section aria-label="Company details" class="v-strip">
            <dl class="v-wrap v-facts">
                <div v-for="fact in companyFacts" :key="fact.label">
                    <dt>{{ fact.label }}</dt>
                    <dd>{{ fact.value }}</dd>
                </div>
            </dl>
        </section>

        <MarketingCtaBand title="Let's build something that works here." lead="Meet us in Lusaka or online. We'll listen first, then tell you honestly what it would take.">
            <WhatsAppButton variant="inline" label="Book a consultation" message="Hi, I'd like to book a consultation with Varidian." class="v-btn-wa" />
            <a href="/products" class="v-btn-ghost">See our products</a>
        </MarketingCtaBand>
    </div>
</template>
