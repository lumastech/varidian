<script setup lang="ts">
import MarketingCtaBand from '@/components/marketing/MarketingCtaBand.vue';
import MarketingPageHero from '@/components/marketing/MarketingPageHero.vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

const anchors = [
    { href: '#custom', label: 'Custom software' },
    { href: '#ai', label: 'AI & automation' },
    { href: '#hosting', label: 'Zambian hosting' },
    { href: '#consulting', label: 'Consulting & support' },
];

const customIncludes = [
    'Requirements & process mapping',
    'UX design & screen approval',
    'Web & mobile-friendly apps',
    'Mobile money & SMS integration',
    'ZRA Smart Invoice integration',
    'Roles, permissions & audit logs',
    'Testing & staff training',
    'Source code handover option',
];

const aiCapabilities = [
    { title: 'Document processing', body: 'Extract data from invoices, forms and applications.' },
    { title: 'Assistants', body: 'Answer staff and customer questions from your own documents.' },
    { title: 'Workflow automation', body: 'Connect systems and remove manual re-typing.' },
    { title: 'Local inference', body: 'Models run on servers in Zambia for sensitive data.' },
];

const consultingServices = [
    { label: 'Digital strategy', value: 'Roadmaps for moving off paper and spreadsheets.' },
    { label: 'System audits', value: 'Reviews of existing software, security and data handling.' },
    { label: 'Standard support', value: 'Included with hosted products and systems.' },
    { label: 'Premium support & SLAs', value: 'Agreed response times and day-to-day IT help.' },
];

const engagementModels = [
    { title: 'Fixed-price project', body: 'An agreed scope and a single quotation, paid in milestones.' },
    { title: 'Monthly subscription', body: 'Use a Varidian product on our cloud for a setup fee plus a monthly fee.' },
    { title: 'One-off licence', body: 'Own the system and its customisations, installed on your own infrastructure.' },
];

useScrollReveal();
</script>

<template>
    <SeoHead
        title="Services — Custom software, AI, hosting & consulting | Varidian"
        description="Custom software, practical AI and automation, Zambian web hosting, and consulting and support — from a first conversation to a system your team runs every day."
        canonical-url="https://varidianlab.com/services"
    />

    <div class="v-landing">
        <MarketingPageHero eyebrow="Services" title="From a first conversation to a system your team runs every day." lead="Four ways we help — use one, or all of them together.">
            <nav class="v-anchors" aria-label="Services">
                <a v-for="anchor in anchors" :key="anchor.href" :href="anchor.href">{{ anchor.label }}</a>
            </nav>
        </MarketingPageHero>

        <!-- ══════════════ 01 CUSTOM SOFTWARE ══════════════ -->
        <section id="custom" class="v-sec">
            <div class="v-wrap v-split" data-reveal>
                <div>
                    <div class="v-eyebrow">01 · Custom software</div>
                    <h2 class="v-sec-title">Systems shaped around <em>how you work.</em></h2>
                    <p class="v-sec-lead">
                        When an off-the-shelf product doesn't fit, we design and build one that does — web platforms, client portals, management systems and internal tools, with local payments and compliance built in.
                    </p>
                </div>
                <div class="v-panel">
                    <div class="v-eyebrow">What's included</div>
                    <ul class="v-checklist">
                        <li v-for="item in customIncludes" :key="item">{{ item }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ══════════════ 02 AI & AUTOMATION ══════════════ -->
        <section id="ai" class="v-sec v-sec-alt">
            <div class="v-wrap v-split" data-reveal>
                <div>
                    <div class="v-eyebrow">02 · AI &amp; automation</div>
                    <h2 class="v-sec-title">Practical AI, <em>without sending your data abroad.</em></h2>
                    <p class="v-sec-lead">
                        We help businesses put AI to work on real tasks — reading documents, answering routine questions, drafting reports and automating repetitive steps — with on-premise options for sensitive information.
                    </p>
                </div>
                <div class="v-mini-grid">
                    <div v-for="capability in aiCapabilities" :key="capability.title" class="v-mini">
                        <h3>{{ capability.title }}</h3>
                        <p>{{ capability.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ 03 ZAMBIAN HOSTING ══════════════ -->
        <section id="hosting" class="v-band">
            <img src="/images/hero-bg.png" alt="" class="v-hero-img" loading="lazy" />
            <div class="v-band-shade" aria-hidden="true"></div>
            <div class="v-hero-grid"></div>
            <div class="v-wrap v-band-in" data-reveal>
                <div>
                    <div class="v-chip">03 · Zambian hosting</div>
                    <h2 class="v-sec-title">Your website and systems, <em>stored in Zambia.</em></h2>
                    <p class="v-band-lead">
                        Shared hosting for websites and managed hosting for business applications, on servers we own and operate locally — priced in kwacha, supported from Lusaka.
                    </p>
                    <a href="/hosting" class="v-band-link">See hosting plans →</a>
                </div>
                <div class="v-int">
                    <div class="v-int-l">Why it matters</div>
                    <p class="v-band-lead">
                        The Data Protection Act, 2021 sets rules on how personal data is stored and transferred. Keeping data at rest in Zambia makes those obligations simpler to meet.
                    </p>
                    <p class="v-band-lead v-band-note">This is general information, not legal advice.</p>
                </div>
            </div>
        </section>

        <!-- ══════════════ 04 CONSULTING & SUPPORT ══════════════ -->
        <section id="consulting" class="v-sec">
            <div class="v-wrap v-split" data-reveal>
                <div>
                    <div class="v-eyebrow">04 · Consulting &amp; support</div>
                    <h2 class="v-sec-title">Advice before you build. <em>Support after you launch.</em></h2>
                    <p class="v-sec-lead">
                        Not sure what you need yet? We'll assess your current setup, recommend options honestly — including ones we don't sell — and stay on to support what you run.
                    </p>
                </div>
                <dl class="v-rows">
                    <div v-for="service in consultingServices" :key="service.label" class="v-row">
                        <dt>{{ service.label }}</dt>
                        <dd>{{ service.value }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- ══════════════ WAYS TO ENGAGE ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Ways to engage</div>
                    <h2 class="v-sec-title">Pay the way that <em>suits your budget.</em></h2>
                </div>
                <div class="v-svc-grid" data-reveal>
                    <div v-for="model in engagementModels" :key="model.title" class="v-card">
                        <h3>{{ model.title }}</h3>
                        <p>{{ model.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <MarketingCtaBand title="Tell us what you're trying to fix.">
            <WhatsAppButton variant="inline" label="Book a consultation" message="Hi, I'd like to book a consultation with Varidian." class="v-btn-wa" />
            <a href="/contact" class="v-btn-ghost">Send an enquiry</a>
        </MarketingCtaBand>
    </div>
</template>

<style>
.v-band-note {
    font-size: 13px;
    font-style: italic;
    margin-top: 12px;
}
</style>
