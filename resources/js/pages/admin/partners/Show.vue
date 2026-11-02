<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import CommissionController from '@/actions/App/Http/Controllers/Admin/CommissionController';
import LeadReviewController from '@/actions/App/Http/Controllers/Admin/LeadReviewController';
import PartnerController from '@/actions/App/Http/Controllers/Admin/PartnerController';
import PayoutController from '@/actions/App/Http/Controllers/Admin/PayoutController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import CommissionTable from '@/components/partners/CommissionTable.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes/admin';
import { index } from '@/routes/admin/partners';
import { show as showPayout } from '@/routes/partner/payouts';
import type {
    CommissionLine,
    CommissionRates,
    PartnerLead,
    PartnerPayout,
    PartnerStatus,
} from '@/types';

type Props = {
    partner: {
        id: number;
        fullName: string;
        email: string;
        phone: string;
        status: PartnerStatus;
        statusLabel: string;
        appliedAt: string;
        nrcNumber: string;
        payoutMethod: string;
        payoutDetails: string;
        agreementAcceptedAt: string;
        due: string;
    };
    leads: (PartnerLead & { team: string | null })[];
    commissions: CommissionLine[];
    payouts: PartnerPayout[];
    teams: { id: number; name: string }[];
    rates: CommissionRates;
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin', href: dashboard() },
            { title: 'Sales partners', href: index() },
        ],
    },
});

const activeLeads = props.leads.filter((lead) =>
    ['confirmed', 'engaged', 'won'].includes(lead.status),
);

const commissionTypes = [
    {
        value: 'subscription',
        label: `Software subscription (${props.rates.subscription}%)`,
    },
    {
        value: 'licence',
        label: `Licence or setup fee (${props.rates.licence}%)`,
    },
    {
        value: 'project',
        label: `Custom project instalment (${props.rates.project}%)`,
    },
    { value: 'referral', label: `Referral only (${props.rates.referral}%)` },
    {
        value: 'adjustment',
        label: 'Adjustment, e.g. refund (enter commission amount)',
    },
];

const leadDecisions = (
    lead: PartnerLead,
): { value: string; label: string }[] => {
    switch (lead.status) {
        case 'pending':
        case 'rejected':
            return [{ value: 'confirm', label: 'Confirm' }];
        case 'confirmed':
            return [
                { value: 'engage', label: 'Meeting or proposal held' },
                { value: 'extend', label: 'Extend ownership' },
                { value: 'lost', label: 'Lost' },
            ];
        case 'engaged':
            return [{ value: 'lost', label: 'Lost' }];
        default:
            return [];
    }
};

const today = new Date().toISOString().slice(0, 10);
</script>

<template>
    <Head :title="props.partner.fullName" />

    <div class="flex flex-col gap-8 p-4">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <Heading
                :title="props.partner.fullName"
                :description="`${props.partner.email} · ${props.partner.phone} · NRC ${props.partner.nrcNumber}`"
            />
            <div class="flex items-center gap-2">
                <Badge
                    :variant="
                        props.partner.status === 'active'
                            ? 'default'
                            : 'secondary'
                    "
                >
                    {{ props.partner.statusLabel }}
                </Badge>
                <Form
                    v-bind="
                        PartnerController.updateStatus.form(props.partner.id)
                    "
                    :options="{ preserveScroll: true }"
                    v-slot="{ processing }"
                >
                    <input
                        type="hidden"
                        name="status"
                        :value="
                            props.partner.status === 'active'
                                ? 'terminated'
                                : 'active'
                        "
                    />
                    <Button
                        size="sm"
                        type="submit"
                        :variant="
                            props.partner.status === 'active'
                                ? 'outline'
                                : 'default'
                        "
                        :disabled="processing"
                    >
                        {{
                            props.partner.status === 'active'
                                ? 'End agreement'
                                : 'Approve'
                        }}
                    </Button>
                </Form>
            </div>
        </div>

        <dl class="grid gap-4 rounded-lg border p-4 text-sm sm:grid-cols-3">
            <div>
                <dt class="text-muted-foreground">Due at next payout</dt>
                <dd class="text-xl font-semibold">
                    ZMW {{ props.partner.due }}
                </dd>
            </div>
            <div>
                <dt class="text-muted-foreground">Pay to</dt>
                <dd>
                    {{ props.partner.payoutMethod.replace('-', ' ') }}:
                    {{ props.partner.payoutDetails }}
                </dd>
            </div>
            <div>
                <dt class="text-muted-foreground">Agreement accepted</dt>
                <dd>{{ props.partner.agreementAcceptedAt }}</dd>
            </div>
        </dl>

        <section class="space-y-4">
            <Heading variant="small" title="Leads" />

            <p
                v-if="props.leads.length === 0"
                class="text-sm text-muted-foreground"
            >
                No leads registered.
            </p>

            <div class="divide-y rounded-lg border">
                <div
                    v-for="lead in props.leads"
                    :key="lead.id"
                    class="space-y-3 p-4 text-sm"
                >
                    <div
                        class="flex flex-wrap items-start justify-between gap-2"
                    >
                        <div>
                            <div class="font-medium">
                                {{ lead.organisation }} · {{ lead.interest }}
                            </div>
                            <div class="text-muted-foreground">
                                {{ lead.contactName }} ·
                                {{ lead.contactPhone }} · registered
                                {{ lead.registeredAt }}
                                <template v-if="lead.status === 'confirmed'">
                                    · owned until {{ lead.expiresAt }}</template
                                >
                                <template v-if="lead.team">
                                    · client account: {{ lead.team }}</template
                                >
                            </div>
                            <div
                                v-if="lead.reviewNote"
                                class="text-muted-foreground"
                            >
                                Note: {{ lead.reviewNote }}
                            </div>
                        </div>
                        <Badge variant="secondary">{{
                            lead.statusLabel
                        }}</Badge>
                    </div>

                    <div
                        v-if="
                            [
                                'pending',
                                'rejected',
                                'confirmed',
                                'engaged',
                            ].includes(lead.status)
                        "
                        class="flex flex-wrap items-start gap-2"
                    >
                        <Form
                            v-for="decision in leadDecisions(lead)"
                            :key="decision.value"
                            v-bind="LeadReviewController.form(lead.id)"
                            :options="{ preserveScroll: true }"
                            v-slot="{ processing }"
                        >
                            <input
                                type="hidden"
                                name="decision"
                                :value="decision.value"
                            />
                            <Button
                                size="sm"
                                variant="outline"
                                type="submit"
                                :disabled="processing"
                            >
                                {{ decision.label }}
                            </Button>
                        </Form>

                        <Form
                            v-if="
                                lead.status === 'confirmed' ||
                                lead.status === 'engaged'
                            "
                            v-bind="LeadReviewController.form(lead.id)"
                            class="flex flex-wrap items-start gap-2"
                            :options="{ preserveScroll: true }"
                            v-slot="{ errors, processing }"
                        >
                            <input type="hidden" name="decision" value="won" />
                            <div>
                                <select
                                    name="team_id"
                                    class="h-8 rounded-md border bg-transparent px-2"
                                    aria-label="Client account"
                                >
                                    <option value="">Client account…</option>
                                    <option
                                        v-for="team in props.teams"
                                        :key="team.id"
                                        :value="team.id"
                                    >
                                        {{ team.name }}
                                    </option>
                                </select>
                                <InputError
                                    :message="errors.team_id ?? errors.decision"
                                />
                            </div>
                            <Button
                                size="sm"
                                type="submit"
                                :disabled="processing"
                                >Signed</Button
                            >
                        </Form>
                    </div>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <Heading
                variant="small"
                title="Record commission"
                description="Hosting is credited automatically when the client pays. Record other payments here once received in full, excluding VAT and third-party costs."
            />

            <Form
                v-bind="CommissionController.store.form(props.partner.id)"
                class="grid gap-4 rounded-lg border p-4 sm:grid-cols-2"
                reset-on-success
                :options="{ preserveScroll: true }"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="type">Revenue type</Label>
                    <select
                        id="type"
                        name="type"
                        class="h-9 rounded-md border bg-transparent px-2 text-sm"
                    >
                        <option
                            v-for="type in commissionTypes"
                            :key="type.value"
                            :value="type.value"
                        >
                            {{ type.label }}
                        </option>
                    </select>
                    <InputError :message="errors.type" />
                </div>
                <div class="grid gap-2">
                    <Label for="lead_id">Client</Label>
                    <select
                        id="lead_id"
                        name="lead_id"
                        class="h-9 rounded-md border bg-transparent px-2 text-sm"
                    >
                        <option value="">None (adjustments only)</option>
                        <option
                            v-for="lead in activeLeads"
                            :key="lead.id"
                            :value="lead.id"
                        >
                            {{ lead.organisation }}
                        </option>
                    </select>
                    <InputError :message="errors.lead_id" />
                </div>
                <div class="grid gap-2">
                    <Label for="amount">Amount received (ZMW)</Label>
                    <Input
                        id="amount"
                        name="amount"
                        type="number"
                        step="0.01"
                        required
                    />
                    <p class="text-xs text-muted-foreground">
                        For adjustments, the commission itself; negative to
                        deduct.
                    </p>
                    <InputError :message="errors.amount" />
                </div>
                <div class="grid gap-2">
                    <Label for="earned_on">Date received</Label>
                    <Input
                        id="earned_on"
                        name="earned_on"
                        type="date"
                        :default-value="today"
                        required
                    />
                    <InputError :message="errors.earned_on" />
                </div>
                <div class="grid gap-2 sm:col-span-2">
                    <Label for="description">Description</Label>
                    <Input
                        id="description"
                        name="description"
                        required
                        placeholder="e.g. BizManager licence, invoice 0042"
                    />
                    <InputError :message="errors.description" />
                </div>
                <div class="sm:col-span-2">
                    <Button type="submit" :disabled="processing"
                        >Record commission</Button
                    >
                </div>
            </Form>
        </section>

        <section class="space-y-4">
            <Heading
                variant="small"
                title="Pay out"
                description="Pays everything earned before this month. Refunds larger than earnings carry over."
            />

            <Form
                v-bind="PayoutController.store.form(props.partner.id)"
                class="flex flex-wrap items-start gap-2"
                reset-on-success
                :options="{ preserveScroll: true }"
                v-slot="{ errors, processing }"
            >
                <select
                    name="method"
                    class="h-9 rounded-md border bg-transparent px-2 text-sm"
                    aria-label="Payment method"
                >
                    <option value="mobile-money">Mobile money</option>
                    <option value="bank-transfer">Bank transfer</option>
                </select>
                <Input
                    name="reference"
                    placeholder="Transaction reference"
                    class="h-9 w-56"
                />
                <Button type="submit" :disabled="processing"
                    >Record ZMW {{ props.partner.due }} paid</Button
                >
                <InputError
                    class="basis-full"
                    :message="errors.payout ?? errors.method"
                />
            </Form>

            <div v-if="props.payouts.length" class="divide-y rounded-lg border">
                <Link
                    v-for="payout in props.payouts"
                    :key="payout.id"
                    :href="showPayout(payout.id)"
                    class="flex items-center justify-between p-4 text-sm hover:bg-muted/50"
                >
                    <span>
                        {{ payout.paidAt }} ·
                        {{ payout.method.replace('-', ' ') }}
                        <template v-if="payout.reference">
                            · {{ payout.reference }}</template
                        >
                    </span>
                    <span class="font-medium">ZMW {{ payout.amount }}</span>
                </Link>
            </div>
        </section>

        <section class="space-y-4">
            <Heading variant="small" title="Commission" />
            <CommissionTable :commissions="props.commissions" show-paid />
        </section>
    </div>
</template>
