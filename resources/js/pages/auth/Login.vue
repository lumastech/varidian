<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Sign in to Varidian',
        description: 'Access your organisation dashboard',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post(store(), {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <div v-if="status" class="auth-status-ok">{{ status }}</div>

    <form class="flex flex-col gap-5" @submit.prevent="submit">
        <div>
            <label class="auth-field-label" for="email">Email address</label>
            <input
                id="email"
                v-model="form.email"
                type="email"
                class="auth-field-input"
                placeholder="you@organisation.com"
                autocomplete="email"
                autofocus
                tabindex="1"
                required
            />
            <p v-if="form.errors.email" class="auth-field-error">{{ form.errors.email }}</p>
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <label class="auth-field-label" for="password" style="margin-bottom:0">Password</label>
                <a
                    v-if="canResetPassword"
                    :href="request()"
                    class="auth-forgot-link"
                    tabindex="5"
                >Forgot password?</a>
            </div>
            <input
                id="password"
                v-model="form.password"
                type="password"
                class="auth-field-input"
                placeholder="••••••••"
                autocomplete="current-password"
                tabindex="2"
                required
                style="margin-top:8px"
            />
            <p v-if="form.errors.password" class="auth-field-error">{{ form.errors.password }}</p>
        </div>

        <label class="flex cursor-pointer items-center gap-2.5">
            <input
                v-model="form.remember"
                type="checkbox"
                class="auth-checkbox"
                tabindex="3"
            />
            <span class="text-sm" style="color:var(--mkt-text-m)">Remember me</span>
        </label>

        <button
            type="submit"
            class="auth-submit-btn"
            tabindex="4"
            :disabled="form.processing"
        >
            <svg
                v-if="form.processing"
                class="h-4 w-4 animate-spin"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity=".25" />
                <path d="M12 2a10 10 0 0110 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
            </svg>
            {{ form.processing ? 'Signing in…' : 'Sign in' }}
        </button>
    </form>
</template>

<style>
.auth-checkbox {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1.5px solid var(--mkt-line-s);
    background: var(--mkt-surface-2);
    cursor: pointer;
    accent-color: #00c9a7;
    flex-shrink: 0;
}
</style>
