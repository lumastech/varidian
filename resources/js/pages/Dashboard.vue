<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import UsageMeter from '@/components/UsageMeter.vue';
import { dashboard, hosting } from '@/routes';
import { show } from '@/routes/invoices';
import { cancel } from '@/routes/subscriptions';
import { edit as editPlan } from '@/routes/subscriptions/plan';
import type { HostingSubscription, HostingUsage, Invoice, Team } from '@/types';

type Props = {
    subscriptions: HostingSubscription[];
    invoices: Invoice[];
    panelUrl: string | null;
    usage?: Record<number, HostingUsage | null>;
};

defineProps<Props>();

defineOptions({
    layout: (props: { currentTeam?: Team | null }) => ({
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: props.currentTeam
                    ? dashboard(props.currentTeam.slug)
                    : '/',
            },
        ],
    }),
});

const page = usePage();
const team = computed(() => page.props.currentTeam as Team);

function cancelSubscription(subscription: HostingSubscription): void {
    const message =
        subscription.status === 'pending'
            ? `Cancel your order for ${subscription.domain}?`
            : `Cancel hosting for ${subscription.domain}? It stays online until ${subscription.renewsAt}, then it is suspended.`;

    if (window.confirm(message)) {
        router.post(
            cancel({
                current_team: team.value.slug,
                subscription: subscription.id,
            }),
        );
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-8 p-4">
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <Heading
                    variant="small"
                    title="Hosting"
                    description="Your websites hosted with Varidian"
                />
                <Button as-child variant="outline">
                    <a :href="hosting().url">Add hosting</a>
                </Button>
            </div>

            <div
                v-if="subscriptions.length === 0"
                class="rounded-lg border border-dashed p-8 text-center text-sm text-muted-foreground"
            >
                You don't have any hosting yet.
                <a :href="hosting().url" class="underline">See plans</a>
            </div>

            <div
                v-for="subscription in subscriptions"
                :key="subscription.id"
                data-test="subscription-row"
                class="flex flex-wrap items-center justify-between gap-4 rounded-lg border p-4"
            >
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{
                            subscription.domain
                        }}</span>
                        <Badge
                            :variant="
                                subscription.status === 'active'
                                    ? 'default'
                                    : 'secondary'
                            "
                        >
                            {{ subscription.statusLabel }}
                        </Badge>
                    </div>
                    <div class="text-sm text-muted-foreground">
                        {{ subscription.plan }} plan · username
                        {{ subscription.username }}
                        <template v-if="subscription.renewsAt">
                            ·
                            {{ subscription.isCancelling ? 'ends' : 'renews' }}
                            {{ subscription.renewsAt }}
                        </template>
                        <template v-if="subscription.scheduledPlan">
                            · moves to {{ subscription.scheduledPlan }} at
                            renewal
                        </template>
                        <template
                            v-if="
                                subscription.status === 'active' &&
                                !subscription.isProvisioned
                            "
                        >
                            · setting up your account
                        </template>
                    </div>
                </div>

                <div class="flex gap-2">
                    <Button
                        v-if="subscription.latestInvoice?.status === 'open'"
                        as-child
                    >
                        <Link
                            :href="
                                show({
                                    current_team: team.slug,
                                    invoice: subscription.latestInvoice.id,
                                })
                            "
                        >
                            Pay ZMW {{ subscription.latestInvoice.amount }}
                        </Link>
                    </Button>
                    <Button
                        v-if="subscription.isProvisioned && panelUrl"
                        as-child
                        variant="outline"
                    >
                        <a :href="panelUrl" target="_blank" rel="noopener"
                            >Control panel</a
                        >
                    </Button>
                    <Button
                        v-if="subscription.canChangePlan"
                        as-child
                        variant="outline"
                    >
                        <Link
                            :href="
                                editPlan({
                                    current_team: team.slug,
                                    subscription: subscription.id,
                                })
                            "
                        >
                            Change plan
                        </Link>
                    </Button>
                    <Button
                        v-if="
                            subscription.status !== 'cancelled' &&
                            !subscription.isCancelling
                        "
                        variant="ghost"
                        data-test="cancel-subscription-button"
                        @click="cancelSubscription(subscription)"
                    >
                        Cancel
                    </Button>
                </div>

                <div
                    v-if="subscription.isProvisioned"
                    class="grid w-full gap-3 sm:grid-cols-4"
                >
                    <template v-if="usage === undefined">
                        <div
                            v-for="n in 4"
                            :key="n"
                            class="h-8 animate-pulse rounded bg-muted"
                        />
                    </template>
                    <template v-else-if="usage[subscription.id]">
                        <UsageMeter
                            label="Storage"
                            unit="mb"
                            :metric="usage[subscription.id]!.disk"
                        />
                        <UsageMeter
                            label="Bandwidth"
                            unit="mb"
                            :metric="usage[subscription.id]!.bandwidth"
                        />
                        <UsageMeter
                            label="Websites"
                            :metric="usage[subscription.id]!.websites"
                        />
                        <UsageMeter
                            label="Databases"
                            :metric="usage[subscription.id]!.databases"
                        />
                    </template>
                    <p
                        v-else
                        class="text-xs text-muted-foreground sm:col-span-4"
                    >
                        Usage is unavailable right now.
                    </p>
                </div>
            </div>
        </section>

        <section v-if="invoices.length" class="space-y-4">
            <Heading variant="small" title="Invoices" />

            <div class="divide-y rounded-lg border">
                <Link
                    v-for="invoice in invoices"
                    :key="invoice.id"
                    :href="
                        show({ current_team: team.slug, invoice: invoice.id })
                    "
                    class="flex items-center justify-between gap-4 p-4 text-sm hover:bg-muted/50"
                >
                    <div>
                        <div class="font-medium">{{ invoice.number }}</div>
                        <div class="text-muted-foreground">
                            {{ invoice.description }}
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span>ZMW {{ invoice.amount }}</span>
                        <Badge
                            :variant="
                                invoice.status === 'paid'
                                    ? 'default'
                                    : 'secondary'
                            "
                        >
                            {{ invoice.statusLabel }}
                        </Badge>
                    </div>
                </Link>
            </div>
        </section>
    </div>
</template>
