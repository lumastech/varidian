<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineOptions({
    layout: {
        title: 'Create your Varidian account',
        description: 'Get started with your organisation dashboard',
    },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post(store().url, {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <form class="flex flex-col gap-5" @submit.prevent="submit">
        <div>
            <label class="auth-field-label" for="name">Full name</label>
            <input
                id="name"
                v-model="form.name"
                type="text"
                class="auth-field-input"
                placeholder="Jane Banda"
                autocomplete="name"
                autofocus
                tabindex="1"
                required
            />
            <p v-if="form.errors.name" class="auth-field-error">{{ form.errors.name }}</p>
        </div>

        <div>
            <label class="auth-field-label" for="email">Email address</label>
            <input
                id="email"
                v-model="form.email"
                type="email"
                class="auth-field-input"
                placeholder="you@organisation.com"
                autocomplete="email"
                tabindex="2"
                required
            />
            <p v-if="form.errors.email" class="auth-field-error">{{ form.errors.email }}</p>
        </div>

        <div>
            <label class="auth-field-label" for="password">Password</label>
            <input
                id="password"
                v-model="form.password"
                type="password"
                class="auth-field-input"
                placeholder="••••••••"
                autocomplete="new-password"
                tabindex="3"
                required
            />
            <p v-if="form.errors.password" class="auth-field-error">{{ form.errors.password }}</p>
        </div>

        <div>
            <label class="auth-field-label" for="password_confirmation">Confirm password</label>
            <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="auth-field-input"
                placeholder="••••••••"
                autocomplete="new-password"
                tabindex="4"
                required
            />
            <p v-if="form.errors.password_confirmation" class="auth-field-error">{{ form.errors.password_confirmation }}</p>
        </div>

        <button
            type="submit"
            class="auth-submit-btn"
            tabindex="5"
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
            {{ form.processing ? 'Creating account…' : 'Create account' }}
        </button>

        <p class="text-center text-sm" style="color:var(--mkt-text-m)">
            Already have an account?
            <a :href="login().url" class="auth-forgot-link" tabindex="6">Sign in</a>
        </p>
    </form>
</template>
