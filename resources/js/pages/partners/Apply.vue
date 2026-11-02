<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PartnerApplicationController from '@/actions/App/Http/Controllers/Partners/PartnerApplicationController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PartnerAgreement from '@/components/partners/PartnerAgreement.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { partners } from '@/routes';
import { apply } from '@/routes/partner';
import type { CommissionRates } from '@/types';

type Props = {
    rates: CommissionRates;
    leadOwnershipDays: number;
    recurringMonths: number;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Become a sales partner', href: apply() }],
    },
});

const fullName = ref('');
const nrcNumber = ref('');
const today = new Date().toISOString().slice(0, 10);
</script>

<template>
    <Head title="Become a sales partner" />

    <div class="mx-auto flex w-full max-w-3xl flex-col gap-6 p-4">
        <Heading
            title="Become a Varidian Sales Partner"
            description="Earn commission on every client you bring to Varidian, paid once they pay."
        />

        <div class="rounded-lg border p-4 text-sm">
            <ul class="grid gap-1 sm:grid-cols-2">
                <li>
                    Software subscriptions: {{ rates.subscription }}% for
                    {{ recurringMonths }} months
                </li>
                <li>
                    Hosting and domains: {{ rates.hosting }}% for
                    {{ recurringMonths }} months
                </li>
                <li>Licences and setup: {{ rates.licence }}% once</li>
                <li>
                    Custom projects: {{ rates.project }}% of each instalment
                </li>
                <li>
                    Referral only: {{ rates.referral }}% of the first payment
                </li>
                <li>
                    Leads are yours for {{ leadOwnershipDays }} days once
                    confirmed
                </li>
            </ul>
            <Link
                :href="partners()"
                class="mt-3 inline-block underline underline-offset-4"
            >
                Read the full programme terms
            </Link>
        </div>

        <Form
            v-bind="PartnerApplicationController.store.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="full_name">Full name (as on your NRC)</Label>
                    <Input
                        id="full_name"
                        v-model="fullName"
                        name="full_name"
                        required
                        autocomplete="name"
                    />
                    <InputError :message="errors.full_name" />
                </div>

                <div class="grid gap-2">
                    <Label for="nrc_number">NRC number</Label>
                    <Input
                        id="nrc_number"
                        v-model="nrcNumber"
                        name="nrc_number"
                        required
                        placeholder="123456/10/1"
                    />
                    <InputError :message="errors.nrc_number" />
                </div>

                <div class="grid gap-2">
                    <Label for="phone">Phone / WhatsApp</Label>
                    <Input
                        id="phone"
                        name="phone"
                        type="tel"
                        required
                        autocomplete="tel"
                        placeholder="+260 97 1234567"
                    />
                    <InputError :message="errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="payout_method">How should we pay you?</Label>
                    <select
                        id="payout_method"
                        name="payout_method"
                        class="h-9 rounded-md border bg-transparent px-2 text-sm"
                    >
                        <option value="mobile-money">Mobile money</option>
                        <option value="bank-transfer">Bank transfer</option>
                    </select>
                    <InputError :message="errors.payout_method" />
                </div>

                <div class="grid gap-2 sm:col-span-2">
                    <Label for="payout_details">Payment details</Label>
                    <Input
                        id="payout_details"
                        name="payout_details"
                        required
                        placeholder="e.g. Airtel Money 0971234567, or bank, branch and account number"
                    />
                    <InputError :message="errors.payout_details" />
                </div>
            </div>

            <section class="space-y-3">
                <h2 class="font-medium">Sales Partner Agreement</h2>
                <div class="max-h-96 overflow-y-auto rounded-lg border p-4">
                    <PartnerAgreement
                        :partner-name="fullName"
                        :nrc-number="nrcNumber"
                        :effective-from="today"
                    />
                </div>

                <label class="flex items-start gap-2 text-sm">
                    <input
                        type="checkbox"
                        name="accept_agreement"
                        value="1"
                        class="mt-1"
                    />
                    <span>
                        I have read and accept the Sales Partner Agreement, and
                        I'm responsible for declaring my commission income for
                        tax purposes.
                    </span>
                </label>
                <InputError :message="errors.accept_agreement" />
            </section>

            <Button type="submit" :disabled="processing">Sign and apply</Button>
        </Form>
    </div>
</template>
