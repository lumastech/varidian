<script setup lang="ts">
defineProps<{
    title: string;
    lead?: string;
}>();
</script>

<template>
    <section class="v-sec">
        <div class="v-wrap">
            <div class="v-cta v-cta--band" data-reveal>
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
