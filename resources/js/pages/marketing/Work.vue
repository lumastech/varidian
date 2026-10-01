<script setup lang="ts">
import MarketingCtaBand from '@/components/marketing/MarketingCtaBand.vue';
import MarketingPageHero from '@/components/marketing/MarketingPageHero.vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

interface CaseStudy {
    tag: string;
    client: string;
    preview: string;
    /** Rows render in order; leave a key out until the client has confirmed the wording. */
    details: { label: 'Challenge' | 'Solution' | 'Result'; value: string }[];
}

const caseStudies: CaseStudy[] = [
    {
        tag: 'Case study · NGO · Southern Province',
        client: "Choma District Women's Development Association",
        preview: 'Choma DWDA dashboard',
        details: [{ label: 'Solution', value: 'A seven-module management system with an admin backend and public website at chomadwda.org.' }],
    },
    {
        tag: 'Case study · Microfinance',
        client: 'ZMAI village banking platform',
        preview: 'Loans module',
        details: [{ label: 'Solution', value: 'A village banking and microfinance platform for members, savings, loans and repayments.' }],
    },
];

const alsoBuilt = ['Corporate websites', 'School systems', 'Church systems', 'Hosted client sites'];

useScrollReveal();
</script>

<template>
    <SeoHead
        title="Our work — Systems running in the field | Varidian"
        description="Real systems, used every day by Zambian organisations — including a seven-module management system for Choma DWDA and the ZMAI village banking platform."
        canonical-url="https://varidianlab.com/work"
    />

    <div class="v-landing">
        <MarketingPageHero eyebrow="Our work" title="Real systems, used every day by Zambian organisations." />

        <!-- ══════════════ CASE STUDIES ══════════════ -->
        <section
            v-for="(study, i) in caseStudies"
            :key="study.client"
            class="v-sec"
            :class="{ 'v-sec-alt': i % 2 === 1 }"
        >
            <div class="v-wrap v-case" :class="{ 'v-case--flip': i % 2 === 1 }" data-reveal>
                <div class="v-work-shot" aria-hidden="true">
                    <span>{{ study.preview }}</span>
                </div>
                <div>
                    <div class="v-prod-sector">{{ study.tag }}</div>
                    <h2>{{ study.client }}</h2>
                    <dl class="v-rows v-rows--compact">
                        <div v-for="detail in study.details" :key="detail.label" class="v-row">
                            <dt>{{ detail.label }}</dt>
                            <dd>{{ detail.value }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        <!-- ══════════════ ALSO BUILT ══════════════ -->
        <section aria-label="Also built by Varidian" class="v-strip">
            <div class="v-wrap v-strip-in">
                <span class="v-strip-l">Also built by Varidian</span>
                <span v-for="item in alsoBuilt" :key="item" class="v-strip-v">{{ item }}</span>
            </div>
        </section>

        <MarketingCtaBand title="Your organisation could be next.">
            <WhatsAppButton variant="inline" label="Start a project" message="Hi, I'd like to start a project with Varidian." class="v-btn-wa" />
            <a href="/services" class="v-btn-ghost">View our services</a>
        </MarketingCtaBand>
    </div>
</template>
