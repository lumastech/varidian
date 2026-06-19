<script setup lang="ts">
import { login } from '@/routes';

defineOptions({
    layout: {
        title: 'Registration closed',
        description: 'Account registration is not available',
    },
});
</script>

<template>
    <div class="flex flex-col items-center gap-5 text-center">
        <div style="color:var(--mkt-text-m);font-size:14px;line-height:1.7">
            Account registration is by invitation only.<br>
            Please contact your administrator.
        </div>
        <a :href="login()" class="auth-submit-btn" style="text-decoration:none;padding:10px 28px;max-width:200px">
            Back to sign in
        </a>
    </div>
</template>
