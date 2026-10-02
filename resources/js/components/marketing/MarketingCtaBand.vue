<script setup lang="ts">
withDefaults(
    defineProps<{
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
    <section class="v-sec">
        <div class="v-wrap">
            <div class="v-cta v-cta--band" data-reveal>
                <img :src="image" alt="" class="v-hero-img" loading="lazy" />
                <div class="v-cta-shade" aria-hidden="true"></div>
                <div>
                    <h2 class="v-sec-title">{{ title }}</h2>
                    <p v-if="lead" class="v-cta-lead">{{ lead }}</p>
                </div>
                <div class="v-cta-row">
                    <slot />
                </div>
            </div>
        </div>
    </section>
</template>
