<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

interface Props {
    title?: string;
    description?: string;
    keywords?: string;
    ogTitle?: string;
    ogDescription?: string;
    ogImage?: string;
    ogType?: string;
    canonicalUrl?: string;
}

const DEFAULT_DESCRIPTION =
    'We design and build web, mobile and desktop management systems for African institutions — purpose-built for the local context. ECZ exams, ZRA tax compliance, Airtel, MTN and Zamtel mobile money, NAPSA payroll, and offline-first where connectivity is limited.';

const DEFAULT_KEYWORDS =
    'varidianlab, varidian lab, varidian consulting, school management system Africa, church management system Zambia, ZRA Smart Invoice, business management system Zambia, village banking software, VSLA management, offline-first software Africa, ECZ exam tracking, NAPSA payroll Zambia, Airtel Money integration, Zamtel Kwacha, MTN MoMo, management systems Africa, Varidian, Zambia software development, African school software, microfinance software Africa';

const props = withDefaults(defineProps<Props>(), {
    title: 'Varidian Consulting Limited — Software that understands Africa',
    description: DEFAULT_DESCRIPTION,
    keywords: DEFAULT_KEYWORDS,
    ogTitle: 'Varidian Consulting Limited',
    ogDescription: DEFAULT_DESCRIPTION,
    ogImage: '/images/og-default.png',
    ogType: 'website',
    canonicalUrl: '',
});
</script>

<template>
    <Head>
        <title>{{ props.title }}</title>
        <meta name="description" :content="props.description" />
        <meta name="keywords" :content="props.keywords" />

        <!-- Open Graph (Facebook, WhatsApp, LinkedIn previews) -->
        <meta property="og:title" :content="props.ogTitle || props.title" />
        <meta property="og:description" :content="props.ogDescription || props.description" />
        <meta property="og:image" :content="props.ogImage" />
        <meta property="og:type" :content="props.ogType" />
        <meta property="og:site_name" content="Varidian Consulting Limited" />
        <meta property="og:locale" content="en" />
        <link v-if="props.canonicalUrl" rel="canonical" :href="props.canonicalUrl" />

        <!-- Twitter / X Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="props.ogTitle || props.title" />
        <meta name="twitter:description" :content="props.ogDescription || props.description" />
        <meta name="twitter:image" :content="props.ogImage" />

        <!-- Regional signals -->
        <meta name="geo.region" content="ZM" />
        <meta name="geo.placename" content="Chibombo, Zambia" />
        <meta name="author" content="Varidian Consulting Limited" />
    </Head>
</template>
