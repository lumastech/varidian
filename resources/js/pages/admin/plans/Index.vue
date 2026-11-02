<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes/admin';
import { create, edit, index } from '@/routes/admin/plans';
import type { HostingPlan } from '@/types';

type AdminPlan = HostingPlan & {
    id: number;
    hestiaPackage: string;
    isActive: boolean;
    customers: number;
};

type Props = {
    plans: AdminPlan[];
    packageMismatches?: Record<string, string[]> | null;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin', href: dashboard() },
            { title: 'Plans', href: index() },
        ],
    },
});
</script>

<template>
    <Head title="Hosting plans" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <Heading
                variant="small"
                title="Hosting plans"
                description="Price changes show on the website immediately and apply to existing customers from their next renewal invoice."
            />
            <Button as-child>
                <Link :href="create()"><Plus /> New plan</Link>
            </Button>
        </div>

        <p
            v-if="packageMismatches === null"
            class="text-sm text-muted-foreground"
        >
            Couldn't reach the Hestia server to compare packages.
        </p>

        <div class="grid gap-4 md:grid-cols-2">
            <div
                v-for="plan in plans"
                :key="plan.id"
                data-test="admin-plan-row"
                class="flex flex-col gap-3 rounded-lg border p-4"
                :class="{ 'opacity-60': !plan.isActive }"
            >
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ plan.name }}</span>
                        <Badge v-if="plan.featured">Featured</Badge>
                        <Badge v-if="!plan.isActive" variant="secondary"
                            >Not on sale</Badge
                        >
                    </div>
                    <span class="text-lg font-semibold"
                        >ZMW {{ plan.price }}
                        <span class="text-sm font-normal text-muted-foreground"
                            >/month</span
                        ></span
                    >
                </div>

                <div class="text-sm text-muted-foreground">
                    {{ plan.features.join(' · ') }}
                </div>

                <div class="text-sm">
                    Hestia package
                    <span class="font-mono">{{ plan.hestiaPackage }}</span>
                    · {{ plan.customers }}
                    {{ plan.customers === 1 ? 'customer' : 'customers' }}
                </div>

                <div
                    v-if="packageMismatches === undefined"
                    class="h-4 w-1/2 animate-pulse rounded bg-muted"
                />
                <ul
                    v-else-if="packageMismatches?.[plan.slug]"
                    data-test="package-mismatch"
                    class="rounded-md bg-destructive/10 p-2 text-sm text-destructive"
                >
                    <li
                        v-for="problem in packageMismatches[plan.slug]"
                        :key="problem"
                    >
                        {{ problem }}
                    </li>
                </ul>

                <div class="mt-auto">
                    <Button as-child variant="outline" size="sm">
                        <Link :href="edit(plan.slug)">Edit</Link>
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
