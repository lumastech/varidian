<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import CommissionTable from '@/components/partners/CommissionTable.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes/partner';
import type { CommissionLine, PartnerPayout } from '@/types';

type Props = {
    payout: PartnerPayout;
    partner: { fullName: string; payoutDetails: string };
    commissions: CommissionLine[];
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Partner portal', href: dashboard() },
            { title: 'Statement', href: '#' },
        ],
    },
});

const print = () => window.print();
</script>

<template>
    <Head :title="`Commission statement ${props.payout.paidAt}`" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <Heading
                title="Commission statement"
                :description="`Varidian Consulting Limited · paid to ${props.partner.fullName} on ${props.payout.paidAt}`"
            />
            <Button variant="outline" class="print:hidden" @click="print"
                >Print</Button
            >
        </div>

        <dl class="grid gap-4 rounded-lg border p-4 text-sm sm:grid-cols-3">
            <div>
                <dt class="text-muted-foreground">Amount paid</dt>
                <dd class="text-xl font-semibold">
                    ZMW {{ props.payout.amount }}
                </dd>
            </div>
            <div>
                <dt class="text-muted-foreground">Paid by</dt>
                <dd>
                    {{ props.payout.method.replace('-', ' ') }} to
                    {{ props.partner.payoutDetails }}
                </dd>
            </div>
            <div>
                <dt class="text-muted-foreground">Reference</dt>
                <dd>{{ props.payout.reference ?? '—' }}</dd>
            </div>
        </dl>

        <CommissionTable :commissions="props.commissions" />

        <p class="text-xs text-muted-foreground">
            Commission is calculated on the amount Varidian received, excluding
            VAT, third-party costs and refunds. Partners are responsible for
            declaring their own commission income for tax purposes.
        </p>
    </div>
</template>
