<script setup lang="ts">
import EnquiryForm from '@/components/marketing/EnquiryForm.vue';
import MarketingPageHero from '@/components/marketing/MarketingPageHero.vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

defineProps<{
    status?: string;
}>();

const contactDetails = [
    {
        label: 'Call or WhatsApp',
        value: '+260 97 1864421',
        href: 'tel:+260971864421',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>`,
    },
    {
        label: 'Email',
        value: 'info@varidianlab.com',
        href: 'mailto:info@varidianlab.com',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>`,
    },
    {
        label: 'Office',
        value: 'Lusaka, Zambia',
        href: null,
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>`,
    },
    {
        label: 'Office hours',
        value: 'Mon–Fri, 08:00–17:00 CAT',
        href: null,
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>`,
    },
];

const nextSteps = [
    { title: 'We reply', body: 'Within one working day, to arrange a short call or visit.' },
    { title: 'We listen', body: 'A free consultation to understand your goals and constraints.' },
    { title: 'We propose', body: 'A clear written proposal and quotation — no obligation.' },
];

useScrollReveal();
</script>

<template>
    <SeoHead
        title="Contact Varidian — Lusaka, Zambia"
        description="Get in touch with Varidian Consulting Limited. Send an enquiry, call, WhatsApp or email us from Lusaka, Zambia. We reply within one working day."
        canonical-url="https://varidianlab.com/contact"
    />

    <div class="v-landing">
        <MarketingPageHero
            eyebrow="Contact us"
            title="Let's talk about what you need."
            lead="Send us a message, call, or drop by the office. We reply within one working day."
        />

        <!-- ══════════════ FORM + DETAILS ══════════════ -->
        <section id="form" class="v-sec">
            <div class="v-wrap v-split" data-reveal>
                <div class="v-cta v-cta--form">
                    <EnquiryForm heading="Send an enquiry" />
                </div>
                <div class="v-panel">
                    <ul class="v-contact-list">
                        <li v-for="detail in contactDetails" :key="detail.label" class="v-contact-item">
                            <!-- eslint-disable-next-line vue/no-v-html -->
                            <div class="v-card-ico" v-html="detail.icon"></div>
                            <div>
                                <div class="v-eyebrow">{{ detail.label }}</div>
                                <a v-if="detail.href" :href="detail.href">{{ detail.value }}</a>
                                <div v-else>{{ detail.value }}</div>
                            </div>
                        </li>
                    </ul>
                    <div class="v-contact-wa">
                        <p>Prefer to chat? Message us on WhatsApp and we'll respond within a few hours.</p>
                        <WhatsAppButton variant="inline" label="Chat on WhatsApp" message="Hi, I'd like to talk to Varidian about a project." class="v-btn-wa" />
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ WHAT HAPPENS NEXT ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Process</div>
                    <h2 class="v-sec-title">What happens <em>next</em></h2>
                </div>
                <div class="v-steps" data-reveal>
                    <div v-for="(step, i) in nextSteps" :key="step.title" class="v-step">
                        <h3>{{ i + 1 }}. {{ step.title }}</h3>
                        <p>{{ step.body }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style>
.v-cta--form {
    display: block;
    padding: 0;
    border-radius: 20px;
}
.v-cta--form .v-form {
    background: transparent;
    border: 0;
    padding: 32px;
}
@media (max-width: 640px) {
    .v-cta--form .v-form {
        padding: 22px;
    }
}
.v-contact-wa {
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid var(--mkt-line);
}
.v-contact-wa p {
    margin-bottom: 16px;
}
</style>
