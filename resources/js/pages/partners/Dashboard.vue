<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import LeadController from '@/actions/App/Http/Controllers/Partners/LeadController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import CommissionTable from '@/components/partners/CommissionTable.vue';
import PartnerAgreement from '@/components/partners/PartnerAgreement.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes/partner';
import { show as showPayout } from '@/routes/partner/payouts';
import type {
    CommissionLine,
    PartnerLead,
    PartnerPayout,
    PartnerStatus,
} from '@/types';

type Props = {
    partner: {
        fullName: string;
        nrcNumber: string;
        status: PartnerStatus;
        statusLabel: string;
        payoutMethod: string;
        payoutDetails: string;
        agreementAcceptedAt: string;
    };
    summary: { due: string; thisMonth: string; paid: string };
    leads: PartnerLead[];
    commissions: CommissionLine[];
    payouts: PartnerPayout[];
    leadOwnershipDays: number;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Partner portal', href: dashboard() }],
    },
});

const products = [
    'HostelHub',
    'Coursify',
    'Varidian Events',
    'VaridianSMS',
    'Varidian Reach',
    'BizManager',
    'Varidian Books',
    'School Management System',
    'Church Management System',
    'Village Banking',
    'Varidian Hosting',
    'Custom software',
];

const leadBadgeVariant = (lead: PartnerLead) => {
    if (
        lead.isExpired ||
        lead.status === 'rejected' ||
        lead.status === 'lost'
    ) {
        return 'outline';
    }

    return lead.status === 'won' ? 'default' : 'secondary';
};
</script>

<template>
    <Head title="Partner portal" />

    <div class="flex flex-col gap-8 p-4">
        <Heading
            :title="`Hello, ${partner.fullName}`"
            description="Register leads, follow their progress and see what you've earned."
        />

        <div
            v-if="partner.status === 'pending'"
            class="rounded-lg border p-4 text-sm"
        >
            <div class="font-medium">Your application is being reviewed</div>
            <p class="text-muted-foreground">
                We approve new partners within 2 working days. You'll be able to
                register leads here once you're approved.
            </p>
        </div>

        <div
            v-else-if="partner.status === 'terminated'"
            class="rounded-lg border p-4 text-sm"
        >
            <div class="font-medium">Your partner agreement has ended</div>
            <p class="text-muted-foreground">
                You can no longer register leads. We still pay commission on
                clients you signed before it ended, for the rest of their
                commission period.
            </p>
        </div>

        <div
            v-if="partner.status !== 'pending'"
            class="grid gap-4 sm:grid-cols-3"
        >
            <div class="rounded-lg border p-4">
                <div class="text-sm text-muted-foreground">
                    Due at next payout
                </div>
                <div class="text-2xl font-semibold">ZMW {{ summary.due }}</div>
                <div class="text-xs text-muted-foreground">
                    Paid by the 10th
                </div>
            </div>
            <div class="rounded-lg border p-4">
                <div class="text-sm text-muted-foreground">
                    Earned this month
                </div>
                <div class="text-2xl font-semibold">
                    ZMW {{ summary.thisMonth }}
                </div>
                <div class="text-xs text-muted-foreground">Paid next month</div>
            </div>
            <div class="rounded-lg border p-4">
                <div class="text-sm text-muted-foreground">
                    Paid to you so far
                </div>
                <div class="text-2xl font-semibold">ZMW {{ summary.paid }}</div>
            </div>
        </div>

        <section v-if="partner.status === 'active'" class="space-y-4">
            <Heading
                variant="small"
                title="Register a lead"
                :description="`The first partner to register a lead owns it for ${leadOwnershipDays} days once we confirm it.`"
            />

            <Form
                v-bind="LeadController.store.form()"
                class="grid gap-4 rounded-lg border p-4 sm:grid-cols-2"
                reset-on-success
                :options="{ preserveScroll: true }"
                v-slot="{ errors, processing, recentlySuccessful }"
            >
                <div class="grid gap-2">
                    <Label for="organisation">Organisation</Label>
                    <Input id="organisation" name="organisation" required />
                    <InputError :message="errors.organisation" />
                </div>
                <div class="grid gap-2">
                    <Label for="interest">Product or service of interest</Label>
                    <Input
                        id="interest"
                        name="interest"
                        list="lead-products"
                        required
                    />
                    <datalist id="lead-products">
                        <option
                            v-for="product in products"
                            :key="product"
                            :value="product"
                        />
                    </datalist>
                    <InputError :message="errors.interest" />
                </div>
                <div class="grid gap-2">
                    <Label for="contact_name">Contact person</Label>
                    <Input id="contact_name" name="contact_name" required />
                    <InputError :message="errors.contact_name" />
                </div>
                <div class="grid gap-2">
                    <Label for="contact_phone">Contact phone</Label>
                    <Input
                        id="contact_phone"
                        name="contact_phone"
                        type="tel"
                        required
                    />
                    <InputError :message="errors.contact_phone" />
                </div>
                <div class="grid gap-2">
                    <Label for="contact_email">Contact email (optional)</Label>
                    <Input
                        id="contact_email"
                        name="contact_email"
                        type="email"
                    />
                    <InputError :message="errors.contact_email" />
                </div>
                <div class="grid gap-2">
                    <Label for="notes">Notes (optional)</Label>
                    <Input
                        id="notes"
                        name="notes"
                        placeholder="What they need, who else is involved…"
                    />
                    <InputError :message="errors.notes" />
                </div>
                <div class="flex items-center gap-3 sm:col-span-2">
                    <Button type="submit" :disabled="processing"
                        >Register lead</Button
                    >
                    <span
                        v-if="recentlySuccessful"
                        class="text-sm text-muted-foreground"
                    >
                        Registered. We'll confirm within 2 working days.
                    </span>
                </div>
            </Form>
        </section>

        <section v-if="partner.status !== 'pending'" class="space-y-4">
            <Heading variant="small" title="Your leads" />

            <p v-if="leads.length === 0" class="text-sm text-muted-foreground">
                You haven't registered any leads yet.
            </p>

            <div v-else class="divide-y rounded-lg border">
                <div
                    v-for="lead in leads"
                    :key="lead.id"
                    class="flex flex-wrap items-start justify-between gap-2 p-4 text-sm"
                >
                    <div>
                        <div class="font-medium">{{ lead.organisation }}</div>
                        <div class="text-muted-foreground">
                            {{ lead.interest }} · {{ lead.contactName }} ·
                            registered {{ lead.registeredAt }}
                        </div>
                        <div
                            v-if="lead.reviewNote"
                            class="text-muted-foreground"
                        >
                            Varidian: {{ lead.reviewNote }}
                        </div>
                    </div>
                    <div class="text-right">
                        <Badge :variant="leadBadgeVariant(lead)">{{
                            lead.statusLabel
                        }}</Badge>
                        <div
                            v-if="
                                lead.status === 'confirmed' && !lead.isExpired
                            "
                            class="mt-1 text-xs text-muted-foreground"
                        >
                            Yours until {{ lead.expiresAt }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section v-if="partner.status !== 'pending'" class="space-y-4">
            <Heading variant="small" title="Commission" />
            <CommissionTable :commissions="commissions" show-paid />
        </section>

        <section v-if="payouts.length > 0" class="space-y-4">
            <Heading variant="small" title="Payments and statements" />
            <div class="divide-y rounded-lg border">
                <Link
                    v-for="payout in payouts"
                    :key="payout.id"
                    :href="showPayout(payout.id)"
                    class="flex items-center justify-between p-4 text-sm hover:bg-muted/50"
                >
                    <span
                        >{{ payout.paidAt }} ·
                        {{ payout.method.replace('-', ' ') }}</span
                    >
                    <span class="font-medium">ZMW {{ payout.amount }}</span>
                </Link>
            </div>
        </section>

        <details class="rounded-lg border p-4">
            <summary class="cursor-pointer text-sm font-medium">
                Your Sales Partner Agreement (accepted
                {{ partner.agreementAcceptedAt }})
            </summary>
            <div class="mt-4">
                <PartnerAgreement
                    :partner-name="partner.fullName"
                    :nrc-number="partner.nrcNumber"
                    :effective-from="partner.agreementAcceptedAt"
                />
            </div>
            <p class="mt-4 text-sm text-muted-foreground">
                We pay you by {{ partner.payoutMethod.replace('-', ' ') }}:
                {{ partner.payoutDetails }}. Message us to change this.
            </p>
        </details>
    </div>
</template>
