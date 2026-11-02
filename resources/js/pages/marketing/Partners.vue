<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import MarketingCtaBand from '@/components/marketing/MarketingCtaBand.vue';
import MarketingPageHero from '@/components/marketing/MarketingPageHero.vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';
import { apply } from '@/routes/partner';
import type { CommissionRates } from '@/types';

type Props = {
    rates: CommissionRates;
    leadOwnershipDays: number;
    recurringMonths: number;
};

const props = defineProps<Props>();

const roles = [
    {
        title: 'You open doors',
        body: 'Introduce Varidian to organisations you know and build the relationship with them.',
    },
    {
        title: 'We do the selling',
        body: 'Varidian runs the demos, scopes the work, sets the price, signs the client and delivers.',
    },
    {
        title: 'You get paid',
        body: 'You earn a share of what the client actually pays, every time they pay, for the periods below.',
    },
];

const commissionRows = [
    {
        label: 'Software subscriptions',
        value: `${props.rates.subscription}% of subscription fees for the client's first ${props.recurringMonths} months — HostelHub, Coursify, Varidian Events, VaridianSMS, Varidian Reach`,
    },
    {
        label: 'Licences and setup',
        value: `${props.rates.licence}% once, when paid — BizManager or Varidian Books licences, installation and training`,
    },
    {
        label: 'Custom software projects',
        value: `${props.rates.project}% of project value, paid in step with each client instalment`,
    },
    {
        label: 'Hosting and domains',
        value: `${props.rates.hosting}% of Varidian Hosting fees for the first ${props.recurringMonths} months`,
    },
    {
        label: 'Referral only',
        value: `${props.rates.referral}% of the first payment when you pass on a lead and take no further part`,
    },
];

const paymentTerms = [
    {
        title: 'Earned when the client pays',
        body: "Commission is earned once Varidian receives the client's payment in full for that invoice, excluding VAT, third-party costs and refunds.",
    },
    {
        title: 'Paid by the 10th',
        body: "We pay last month's commission by the 10th of the following month, by mobile money or bank transfer.",
    },
    {
        title: 'A clear statement',
        body: 'Every payment comes with a statement showing each client, the amount we received and the commission due.',
    },
    {
        title: 'Refunds',
        body: 'If a client is refunded, the commission on that amount is deducted from your next payment.',
    },
];

const leadSteps = [
    {
        title: 'Register the lead',
        body: "Add the organisation, contact person and the product they're interested in from your partner portal.",
    },
    {
        title: 'We confirm',
        body: "Within 2 working days we confirm it's yours, or let you know it's already registered or an existing client.",
    },
    {
        title: `It's yours for ${props.leadOwnershipDays} days`,
        body: `If no meeting or proposal happens within ${props.leadOwnershipDays} days, the lead opens up again unless we agree to extend it.`,
    },
];

useScrollReveal();
</script>

<template>
    <SeoHead
        title="Become a Varidian Sales Partner — earn commission on every client"
        description="Introduce Zambian organisations to Varidian's software, hosting and custom development and earn commission every time they pay. No targets, no fixed hours."
        canonical-url="https://varidianlab.com/partners"
    />

    <div class="v-landing">
        <MarketingPageHero
            eyebrow="Sales Partner Programme"
            title="Know organisations that need better systems? Earn commission when they sign with us."
            lead="Varidian Sales Partners are independent: no salary, no fixed hours and no targets you can be penalised for. You open the door, we close the deal, and you get paid when the client pays."
        />

        <!-- ══════════════ HOW IT WORKS ══════════════ -->
        <section class="v-sec">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">How it works</div>
                    <h2 class="v-sec-title">
                        You bring the relationship. <em>We handle the rest.</em>
                    </h2>
                </div>
                <div class="v-steps" data-reveal>
                    <div
                        v-for="(role, i) in roles"
                        :key="role.title"
                        class="v-step"
                    >
                        <h3>{{ i + 1 }}. {{ role.title }}</h3>
                        <p>{{ role.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ COMMISSION ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap v-split" data-reveal>
                <div>
                    <div class="v-chip">Commission</div>
                    <h2 class="v-sec-title">
                        A share of what the client <em>actually pays.</em>
                    </h2>
                    <p class="v-sec-lead">
                        Calculated on the amount we receive, excluding VAT,
                        third-party costs such as hardware, SMS credits and
                        gateway fees, and refunds.
                    </p>
                </div>
                <dl class="v-rows">
                    <div
                        v-for="row in commissionRows"
                        :key="row.label"
                        class="v-row"
                    >
                        <dt>{{ row.label }}</dt>
                        <dd>{{ row.value }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- ══════════════ PAYMENT TERMS ══════════════ -->
        <section class="v-sec">
            <div class="v-wrap v-why" data-reveal>
                <div class="v-why-head">
                    <div class="v-chip">Payment terms</div>
                    <h2 class="v-sec-title">
                        Paid monthly, <em>with a statement.</em>
                    </h2>
                </div>
                <ol class="v-why-list">
                    <li
                        v-for="(term, i) in paymentTerms"
                        :key="term.title"
                        class="v-why-row"
                    >
                        <span class="v-why-n">{{
                            String(i + 1).padStart(2, '0')
                        }}</span>
                        <div>
                            <h3>{{ term.title }}</h3>
                            <p>{{ term.body }}</p>
                        </div>
                    </li>
                </ol>
            </div>
        </section>

        <!-- ══════════════ LEAD OWNERSHIP ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Lead registration</div>
                    <h2 class="v-sec-title">
                        First to register <em>owns the lead.</em>
                    </h2>
                    <p class="v-sec-lead">
                        Existing Varidian clients don't earn commission, except
                        on a new product or project you introduce. Partners
                        working a client together agree a split before the
                        proposal goes out.
                    </p>
                </div>
                <div class="v-steps" data-reveal>
                    <div
                        v-for="(step, i) in leadSteps"
                        :key="step.title"
                        class="v-step"
                    >
                        <h3>{{ i + 1 }}. {{ step.title }}</h3>
                        <p>{{ step.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <MarketingCtaBand
            title="Ready to partner with Varidian?"
            lead="Create an account, sign the partner agreement online, and we'll approve you within 2 working days."
        >
            <Link :href="apply()" class="v-btn-ghost"
                >Apply to become a partner</Link
            >
            <WhatsAppButton
                variant="inline"
                label="Ask us a question"
                message="Hi, I'd like to know more about the Varidian Sales Partner programme."
                class="v-btn-wa"
            />
        </MarketingCtaBand>
    </div>
</template>
