<script setup lang="ts">
withDefaults(
    defineProps<{
        eyebrow: string;
        title: string;
        lead?: string;
        /**
         * Background photo; pass a different path to override per page.
         */
        image?: string;
    }>(),
    {
        image: '/images/hero-bg.png',
    },
);
</script>

<template>
    <section id="top" class="v-hero v-hero--page">
        <img :src="image" alt="" class="v-hero-img" fetchpriority="high" />
        <div class="v-hero-shade" aria-hidden="true"></div>
        <div class="v-hero-grid"></div>
        <div class="v-hero-in" :class="{ 'v-hero-in--single': !$slots.aside }">
            <div class="v-hero-copy">
                <div class="v-pill">
                    <i></i>
                    {{ eyebrow }}
                </div>
                <h1 class="v-hero-title">{{ title }}</h1>
                <p v-if="lead" class="v-hero-body">{{ lead }}</p>
                <slot />
            </div>
            <slot name="aside" />
        </div>
    </section>
</template>
