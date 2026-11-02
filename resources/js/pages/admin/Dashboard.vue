<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import OfflinePaymentController from '@/actions/App/Http/Controllers/Admin/OfflinePaymentController';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { dashboard } from '@/routes/admin';
import { index as partnersIndex } from '@/routes/admin/partners';
import { index as plansIndex } from '@/routes/admin/plans';
import type { Invoice, SubscriptionStatus } from '@/types';

type AdminSubscription = {
    id: number;
    domain: string;
    team: string;
    plan: string;
    username: string;
    status: SubscriptionStatus;
    statusLabel: string;
    renewsAt: string | null;
    isCancelling: boolean;
    isProvisioned: boolean;
    hasSsl: boolean;
};

type Inquiry = {
    id: number;
    name: string;
    organisation: string | null;
    phone: string;
    email: string | null;
    product_interest: string;
    message: string;
    mailed_at: string | null;
    created_at: string;
};

type Props = {
    subscriptions: AdminSubscription[];
    openInvoices: (Invoice & { team: string })[];
    inquiries: Inquiry[];
    partnerItemsToReview: number;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Admin', href: dashboard() }],
    },
});
</script>

<template>
    <Head title="Admin" />

    <div class="flex flex-col gap-8 p-4">
        <div class="flex justify-end gap-2">
            <Button as-child variant="outline">
                <Link :href="partnersIndex()">
                    Sales partners
                    <Badge v-if="partnerItemsToReview > 0">{{
                        partnerItemsToReview
                    }}</Badge>
                </Link>
            </Button>
            <Button as-child variant="outline">
                <Link :href="plansIndex()">Manage plans &amp; prices</Link>
            </Button>
        </div>

        <section class="space-y-4">
            <Heading
                variant="small"
                title="Open invoices"
                description="Record bank transfers and direct mobile money payments here"
            />

            <p
                v-if="openInvoices.length === 0"
                class="text-sm text-muted-foreground"
            >
                Nothing outstanding.
            </p>

            <div
                v-for="invoice in openInvoices"
                :key="invoice.id"
                data-test="open-invoice-row"
                class="flex flex-wrap items-center justify-between gap-4 rounded-lg border p-4 text-sm"
            >
                <div>
                    <div class="font-medium">
                        {{ invoice.number }} · {{ invoice.team }}
                    </div>
                    <div class="text-muted-foreground">
                        {{ invoice.description }} · ZMW {{ invoice.amount }} ·
                        due {{ invoice.dueAt }}
                    </div>
                </div>

                <Form
                    v-bind="OfflinePaymentController.form(invoice.id)"
                    class="flex flex-wrap items-center gap-2"
                    :options="{ preserveScroll: true }"
                    v-slot="{ processing }"
                >
                    <select
                        name="method"
                        class="h-9 rounded-md border bg-transparent px-2"
                        aria-label="Payment method"
                    >
                        <option value="bank-transfer">Bank transfer</option>
                        <option value="mobile-money">Mobile money</option>
                        <option value="cash">Cash</option>
                    </select>
                    <Input
                        name="note"
                        placeholder="Reference / note"
                        class="h-9 w-48"
                    />
                    <Button size="sm" type="submit" :disabled="processing">
                        Mark paid
                    </Button>
                </Form>
            </div>
        </section>

        <section class="space-y-4">
            <Heading variant="small" title="Hosting accounts" />

            <div class="overflow-x-auto rounded-lg border">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50 text-muted-foreground">
                        <tr>
                            <th class="p-3 font-medium">Domain</th>
                            <th class="p-3 font-medium">Customer</th>
                            <th class="p-3 font-medium">Plan</th>
                            <th class="p-3 font-medium">Hestia user</th>
                            <th class="p-3 font-medium">Status</th>
                            <th class="p-3 font-medium">Period ends</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="subscription in subscriptions"
                            :key="subscription.id"
                        >
                            <td class="p-3">
                                {{ subscription.domain }}
                                <Badge
                                    v-if="
                                        subscription.isProvisioned &&
                                        !subscription.hasSsl
                                    "
                                    variant="outline"
                                    >No SSL</Badge
                                >
                            </td>
                            <td class="p-3">{{ subscription.team }}</td>
                            <td class="p-3">{{ subscription.plan }}</td>
                            <td class="p-3 font-mono">
                                {{ subscription.username }}
                            </td>
                            <td class="p-3">
                                <Badge
                                    :variant="
                                        subscription.status === 'active'
                                            ? 'default'
                                            : 'secondary'
                                    "
                                >
                                    {{ subscription.statusLabel }}
                                </Badge>
                                <span
                                    v-if="subscription.isCancelling"
                                    class="ml-1 text-muted-foreground"
                                    >cancelling</span
                                >
                            </td>
                            <td class="p-3">
                                {{ subscription.renewsAt ?? '—' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="space-y-4">
            <Heading variant="small" title="Recent inquiries" />

            <div class="divide-y rounded-lg border">
                <div
                    v-for="inquiry in inquiries"
                    :key="inquiry.id"
                    class="space-y-1 p-4 text-sm"
                >
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-medium">{{ inquiry.name }}</span>
                        <span
                            v-if="inquiry.organisation"
                            class="text-muted-foreground"
                            >{{ inquiry.organisation }}</span
                        >
                        <Badge variant="secondary">{{
                            inquiry.product_interest
                        }}</Badge>
                        <Badge v-if="!inquiry.mailed_at" variant="destructive"
                            >Email failed</Badge
                        >
                    </div>
                    <div class="text-muted-foreground">
                        {{ inquiry.phone }}
                        <template v-if="inquiry.email">
                            · {{ inquiry.email }}</template
                        >
                        · {{ inquiry.created_at.slice(0, 10) }}
                    </div>
                    <p class="whitespace-pre-line">{{ inquiry.message }}</p>
                </div>
            </div>
        </section>
    </div>
</template>
