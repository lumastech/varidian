<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import type { CommissionLine } from '@/types';

defineProps<{
    commissions: CommissionLine[];
    showPaid?: boolean;
}>();
</script>

<template>
    <div class="overflow-x-auto rounded-lg border">
        <table class="w-full text-left text-sm">
            <thead class="bg-muted/50 text-muted-foreground">
                <tr>
                    <th class="p-3 font-medium">Date</th>
                    <th class="p-3 font-medium">Client</th>
                    <th class="p-3 font-medium">Details</th>
                    <th class="p-3 text-right font-medium">Received (ZMW)</th>
                    <th class="p-3 text-right font-medium">Rate</th>
                    <th class="p-3 text-right font-medium">Commission (ZMW)</th>
                    <th v-if="showPaid" class="p-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr v-if="commissions.length === 0">
                    <td
                        :colspan="showPaid ? 7 : 6"
                        class="p-3 text-muted-foreground"
                    >
                        No commission yet.
                    </td>
                </tr>
                <tr v-for="commission in commissions" :key="commission.id">
                    <td class="p-3 whitespace-nowrap">
                        {{ commission.earnedAt }}
                    </td>
                    <td class="p-3">{{ commission.client ?? '—' }}</td>
                    <td class="p-3">
                        <div>{{ commission.typeLabel }}</div>
                        <div class="text-muted-foreground">
                            {{ commission.description }}
                        </div>
                    </td>
                    <td class="p-3 text-right">
                        {{ commission.received ?? '—' }}
                    </td>
                    <td class="p-3 text-right">
                        {{
                            commission.rate === null
                                ? '—'
                                : `${commission.rate}%`
                        }}
                    </td>
                    <td class="p-3 text-right font-medium">
                        {{ commission.amount }}
                    </td>
                    <td v-if="showPaid" class="p-3">
                        <Badge
                            :variant="
                                commission.isPaid ? 'secondary' : 'outline'
                            "
                        >
                            {{ commission.isPaid ? 'Paid' : 'Unpaid' }}
                        </Badge>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
