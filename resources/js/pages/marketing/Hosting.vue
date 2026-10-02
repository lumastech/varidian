<script setup lang="ts">
import MarketingCtaBand from '@/components/marketing/MarketingCtaBand.vue';
import MarketingPageHero from '@/components/marketing/MarketingPageHero.vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

const highlights = [
    { title: 'Stored in Zambia', body: 'Data at rest never leaves the country' },
    { title: 'Billed in ZMW', body: 'No dollar card needed' },
    { title: 'Free SSL', body: 'On every website' },
    { title: 'Local support', body: 'From our Lusaka team' },
];

const plans = [
    {
        name: 'Starter',
        audience: 'For a single business website',
        price: '250',
        features: ['1 website', '5GB SSD storage', 'Free SSL certificate', '1 database', 'Standard support'],
        featured: false,
    },
    {
        name: 'Business',
        audience: 'For growing SMEs',
        price: '1000',
        features: ['5 websites', '20GB SSD storage', 'Free SSL certificates', '5 databases', 'Priority support'],
        featured: true,
    },
    {
        name: 'Developer',
        audience: 'For agencies & freelancers',
        price: '500',
        features: ['3 websites + 3 databases', '10GB SSD storage', 'Multiple PHP versions', 'SSH access', 'Priority support'],
        featured: false,
    },
];

const managedHosting = [
    { label: 'Isolation', value: 'Business applications run separately from shared websites.' },
    { label: 'Encryption', value: 'Secure connections end to end, with certificates held in Zambia.' },
    { label: 'Backups', value: 'Scheduled backups to separate hardware.' },
    { label: 'Updates', value: 'Security patches and application updates handled by us.' },
];

const faqs = [
    { question: 'Can you move my existing website?', answer: "Yes. We'll migrate your site and help point your domain to its new home." },
    { question: 'How do I pay?', answer: 'In kwacha, by mobile money or bank transfer.' },
    { question: 'Where exactly is my data stored?', answer: 'On servers we own and operate in Lusaka, Zambia.' },
];

useScrollReveal();
</script>

<template>
    <SeoHead
        title="Varidian Hosting — Web hosting in Zambia, billed in kwacha"
        description="Web hosting on servers in Lusaka — affordable for SMEs, billed in kwacha, free SSL, and supported by people you can call. Your data, hosted at home."
        keywords="web hosting Zambia, Zambian hosting, hosting Lusaka, kwacha web hosting, Data Protection Act Zambia hosting, managed hosting Zambia, SSD hosting Zambia, Varidian Hosting"
        canonical-url="https://varidianlab.com/hosting"
    />

    <div class="v-landing">
        <MarketingPageHero
            image="/images/hero-bg-girl.jpg"
            eyebrow="Varidian Hosting"
            title="Your data, hosted at home."
            lead="Web hosting on servers in Lusaka — affordable for SMEs, billed in kwacha, supported by people you can call."
        >
            <template #aside>
                <div class="v-int v-int--compact">
                    <div class="v-int-grid">
                        <div v-for="highlight in highlights" :key="highlight.title" class="v-int-item">
                            <div class="v-int-t">{{ highlight.title }}</div>
                            <div class="v-int-b">{{ highlight.body }}</div>
                        </div>
                    </div>
                </div>
            </template>
        </MarketingPageHero>

        <!-- ══════════════ SHARED HOSTING PLANS ══════════════ -->
        <section id="plans" class="v-sec">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Shared hosting plans</div>
                    <h2 class="v-sec-title">Pick a plan. <em>Upgrade any time.</em></h2>
                </div>
                <div class="v-tiers" data-reveal>
                    <div v-for="plan in plans" :key="plan.name" class="v-tier" :class="{ 'v-tier--featured': plan.featured }">
                        <div class="v-tier-head">
                            <div>
                                <h3>{{ plan.name }}</h3>
                                <div class="v-tier-sub">{{ plan.audience }}</div>
                            </div>
                            <span v-if="plan.featured" class="v-tier-badge">Most popular</span>
                        </div>
                        <div class="v-tier-price">ZMW {{ plan.price }}<span>/month</span></div>
                        <ul class="v-checklist v-checklist--single">
                            <li v-for="feature in plan.features" :key="feature">{{ feature }}</li>
                        </ul>
                        <WhatsAppButton
                            variant="inline"
                            :label="`Choose ${plan.name}`"
                            :message="`Hi, I'd like to sign up for the ${plan.name} hosting plan (ZMW ${plan.price}/month).`"
                            class="v-tier-cta"
                            :class="plan.featured ? 'v-btn-wa' : 'v-btn-outline'"
                        />
                    </div>
                </div>
                <p class="v-note" data-reveal>Email hosting is not included. We'll help you set up a reputable email provider alongside your website.</p>
            </div>
        </section>

        <!-- ══════════════ APPLICATION HOSTING ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap v-split" data-reveal>
                <div>
                    <div class="v-chip">Application hosting</div>
                    <h2 class="v-sec-title">Running a business system? <em>We manage it for you.</em></h2>
                    <p class="v-sec-lead">Varidian products and custom systems run on separate, managed servers — isolated from shared hosting, monitored, and backed up.</p>
                    <WhatsAppButton
                        variant="inline"
                        label="Ask about managed hosting →"
                        message="Hi, I'd like to ask about managed hosting for a business system."
                        class="v-prod-demo v-link"
                    />
                </div>
                <dl class="v-rows">
                    <div v-for="item in managedHosting" :key="item.label" class="v-row">
                        <dt>{{ item.label }}</dt>
                        <dd>{{ item.value }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- ══════════════ FAQ ══════════════ -->
        <section class="v-sec">
            <div class="v-wrap v-split" data-reveal>
                <div>
                    <div class="v-chip">FAQ</div>
                    <h2 class="v-sec-title">Common <em>questions</em></h2>
                </div>
                <dl class="v-rows">
                    <div v-for="faq in faqs" :key="faq.question" class="v-row">
                        <dt>{{ faq.question }}</dt>
                        <dd>{{ faq.answer }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <MarketingCtaBand title="Ready to bring your website home?">
            <WhatsAppButton variant="inline" label="Get started" message="Hi, I'd like to get started with Varidian Hosting." class="v-btn-wa" />
            <a href="#plans" class="v-btn-ghost">Compare plans</a>
        </MarketingCtaBand>
    </div>
</template>

<style>
.v-landing .v-split .v-link {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0;
    margin-top: 22px;
    border-radius: 0;
    background: none;
    font-size: 14.5px;
    font-weight: 700;
    color: var(--v-link);
}
.v-landing .v-split .v-link:hover {
    background: none;
    color: var(--v-link-hover);
}
.v-landing .v-split .v-link svg {
    width: 15px;
    height: 15px;
}
</style>
