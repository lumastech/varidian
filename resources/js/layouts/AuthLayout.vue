<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="auth-root">
        <div class="auth-dot-grid"></div>
        <div class="auth-glow"></div>

        <div class="auth-center">
            <Link :href="home()" class="auth-logo-link">
                <AppLogoIcon class="auth-logo fill-current" />
            </Link>

            <div class="auth-card">
                <div v-if="title || description" class="mb-7 text-center">
                    <h1 v-if="title" class="auth-title">{{ title }}</h1>
                    <p v-if="description" class="mt-1.5 text-sm" style="color:var(--mkt-text-m)">{{ description }}</p>
                </div>
                <slot />
            </div>

            <Link :href="home()" class="auth-back-link">
                ← Back to site
            </Link>
        </div>
    </div>
</template>

<style>
.auth-root {
    min-height: 100dvh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--mkt-bg);
    color: var(--mkt-text);
    font-family: 'Inter', sans-serif;
    position: relative;
    overflow: hidden;
    padding: 24px;
}

.auth-dot-grid {
    position: fixed;
    inset: 0;
    pointer-events: none;
    background-image: radial-gradient(circle at 1px 1px, var(--mkt-dot-grid) 1px, transparent 0);
    background-size: 34px 34px;
}

.auth-glow {
    position: absolute;
    top: -180px;
    right: -120px;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, color-mix(in srgb, #00c9a7 15%, transparent), transparent 66%);
    pointer-events: none;
}

.auth-center {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.auth-logo-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.auth-logo {
    height: 28px;
    width: auto;
    color: var(--mkt-text-h);
}

.auth-card {
    width: 100%;
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 20px;
    padding: 36px;
    box-shadow: var(--mkt-shadow);
}

.auth-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: var(--mkt-text-h);
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.auth-back-link {
    font-size: 12px;
    color: var(--mkt-text-m);
    text-decoration: none;
    transition: color 0.2s;
}

.auth-back-link:hover {
    color: #00c9a7;
}

/* ── Form field primitives (shared across all auth pages) ── */
.auth-field-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--mkt-text-m);
    margin-bottom: 8px;
}

.auth-field-input {
    width: 100%;
    background: var(--mkt-surface-2);
    border: 1px solid var(--mkt-line-s);
    border-radius: 10px;
    padding: 11px 15px;
    font-size: 14px;
    color: var(--mkt-text-h);
    font-family: inherit;
    transition: border-color 0.2s, background 0.2s;
    outline: none;
    -webkit-appearance: none;
}

.auth-field-input:focus {
    border-color: rgba(0, 201, 167, 0.5);
    background: var(--mkt-surface);
}

.auth-field-input::placeholder {
    color: var(--mkt-muted);
}

.auth-field-error {
    margin-top: 5px;
    font-size: 12px;
    color: #f87171;
}

.auth-forgot-link {
    font-size: 12px;
    color: var(--mkt-text-m);
    text-decoration: none;
    transition: color 0.2s;
}

.auth-forgot-link:hover {
    color: #00c9a7;
}

.auth-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #00c9a7, #00a3ff);
    color: #080c10;
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: opacity 0.2s;
    margin-top: 4px;
    font-family: inherit;
}

.auth-submit-btn:hover:not(:disabled) {
    opacity: 0.88;
}

.auth-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.auth-status-ok {
    background: rgba(0, 201, 167, 0.1);
    border: 1px solid rgba(0, 201, 167, 0.3);
    border-radius: 10px;
    padding: 12px 16px;
    color: #00c9a7;
    font-size: 13px;
    text-align: center;
    margin-bottom: 20px;
}

.auth-link {
    color: #00c9a7;
    text-decoration: none;
    font-weight: 600;
    transition: opacity 0.2s;
}

.auth-link:hover {
    opacity: 0.8;
}
</style>
