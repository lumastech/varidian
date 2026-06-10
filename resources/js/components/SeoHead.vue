<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

interface Props {
    title?: string;
    description?: string;
    ogTitle?: string;
    ogDescription?: string;
    ogImage?: string;
    ogType?: string;
    canonicalUrl?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Varidian Consulting Limited — Software Solutions for Zambia',
    description:
        'Varidian builds sector-specific management systems for Zambian schools, churches, NGOs, and businesses. ZRA Smart Invoice compliant. Built and supported in Lusaka.',
    ogTitle: 'Varidian Consulting Limited',
    ogDescription: 'Management systems purpose-built for Zambian organisations — schools, churches, NGOs, and SMEs.',
    ogImage: '/images/og-default.png',
    ogType: 'website',
    canonicalUrl: '',
});
</script>

<template>
    <Head>
        <title>{{ props.title }}</title>
        <meta name="description" :content="props.description" />

        <!-- Open Graph (Facebook, WhatsApp, LinkedIn previews) -->
        <meta property="og:title" :content="props.ogTitle || props.title" />
        <meta property="og:description" :content="props.ogDescription || props.description" />
        <meta property="og:image" :content="props.ogImage" />
        <meta property="og:type" :content="props.ogType" />
        <meta property="og:site_name" content="Varidian Consulting Limited" />
        <meta property="og:locale" content="en_ZM" />
        <link v-if="props.canonicalUrl" rel="canonical" :href="props.canonicalUrl" />

        <!-- Twitter / X Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="props.ogTitle || props.title" />
        <meta name="twitter:description" :content="props.ogDescription || props.description" />
        <meta name="twitter:image" :content="props.ogImage" />

        <!-- Additional trust/regional signals -->
        <meta name="geo.region" content="ZM-09" />
        <meta name="geo.placename" content="Lusaka, Zambia" />
        <meta name="author" content="Varidian Consulting Limited" />
    </Head>
</template>
