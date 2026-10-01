<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = withDefaults(
    defineProps<{
        heading?: string;
        interest?: string;
    }>(),
    {
        heading: '',
        interest: '',
    },
);

const enquiryTopics = ['Custom software', 'A Varidian product demo', 'Web hosting', 'AI & automation', 'Consulting or support', 'Something else'];

const form = useForm({
    name: '',
    organisation: '',
    email: '',
    phone: '',
    product_interest: props.interest,
    message: '',
    consent: false,
});

const enquirySent = ref(false);

function sendEnquiry(): void {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            enquirySent.value = true;
        },
    });
}
</script>

<template>
    <form class="v-form" @submit.prevent="sendEnquiry">
        <h2 v-if="heading" class="v-form-title">{{ heading }}</h2>

        <div v-if="enquirySent" class="v-form-ok" role="status">Thanks — your enquiry is on its way. We'll be in touch within one working day.</div>

        <div class="v-form-row">
            <label>
                Full name
                <input v-model="form.name" type="text" autocomplete="name" required />
                <span v-if="form.errors.name" class="v-form-err">{{ form.errors.name }}</span>
            </label>
            <label>
                Organisation
                <input v-model="form.organisation" type="text" autocomplete="organization" />
            </label>
            <label>
                Email
                <input v-model="form.email" type="email" autocomplete="email" />
                <span v-if="form.errors.email" class="v-form-err">{{ form.errors.email }}</span>
            </label>
            <label>
                Phone / WhatsApp
                <input v-model="form.phone" type="tel" autocomplete="tel" placeholder="+260…" required />
                <span v-if="form.errors.phone" class="v-form-err">{{ form.errors.phone }}</span>
            </label>
        </div>
        <label>
            I'm interested in
            <select v-model="form.product_interest" required>
                <option value="" disabled>Select one…</option>
                <option v-for="topic in enquiryTopics" :key="topic">{{ topic }}</option>
            </select>
            <span v-if="form.errors.product_interest" class="v-form-err">{{ form.errors.product_interest }}</span>
        </label>
        <label>
            Tell us about your project
            <textarea v-model="form.message" rows="5" required></textarea>
            <span v-if="form.errors.message" class="v-form-err">{{ form.errors.message }}</span>
        </label>
        <label class="v-form-check">
            <input v-model="form.consent" type="checkbox" required />
            <span>I agree to Varidian storing these details to respond to my enquiry, in line with the privacy policy.</span>
        </label>
        <span v-if="form.errors.consent" class="v-form-err">{{ form.errors.consent }}</span>
        <button type="submit" class="v-btn-wa v-form-btn" :disabled="form.processing">
            {{ form.processing ? 'Sending…' : 'Send enquiry' }}
        </button>
    </form>
</template>
