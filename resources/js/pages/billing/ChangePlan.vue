<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import PlanChangeController from '@/actions/App/Http/Controllers/Billing/PlanChangeController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import type { HostingPlan, Team } from '@/types';

type Props = {
    subscription: {
        id: number;
        domain: string;
        plan: string;
        scheduledPlan: string | null;
        renewsAt: string;
    };
    plans: (HostingPlan & { isUpgrade: boolean; upgradeCost: string | null })[];
};

const props = defineProps<Props>();

defineOptions({
    layout: (props: { currentTeam?: Team | null }) => ({
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: props.currentTeam
                    ? dashboard(props.currentTeam.slug)
                    : '/',
            },
            { title: 'Change plan', href: '#' },
        ],
    }),
});

const page = usePage();
const team = computed(() => page.props.currentTeam as Team);

function actionLabel(plan: Props['plans'][number]): string {
    if (plan.slug === props.subscription.plan) {
        return props.subscription.scheduledPlan
            ? 'Keep this plan'
            : 'Current plan';
    }

    if (plan.slug === props.subscription.scheduledPlan) {
        return `Switching on ${props.subscription.renewsAt}`;
    }

    return plan.isUpgrade
        ? `Upgrade now for ZMW ${plan.upgradeCost}`
        : `Switch on ${props.subscription.renewsAt}`;
}

function isDisabled(plan: Props['plans'][number]): boolean {
    return (
        (plan.slug === props.subscription.plan &&
            !props.subscription.scheduledPlan) ||
        plan.slug === props.subscription.scheduledPlan
    );
}
</script>

<template>
    <Head :title="`Change plan for ${props.subscription.domain}`" />

    <div class="mx-auto flex w-full max-w-4xl flex-col gap-6 p-4">
        <Heading
            :title="`Change plan for ${props.subscription.domain}`"
            description="Upgrades start as soon as you pay the difference for the rest of this month. Downgrades start at your next renewal."
        />

        <Form
            v-bind="
                PlanChangeController.update.form({
                    current_team: team.slug,
                    subscription: props.subscription.id,
                })
            "
            v-slot="{ errors, processing }"
            class="space-y-4"
        >
            <InputError :message="errors.plan" />

            <div class="grid gap-4 sm:grid-cols-2">
                <div
                    v-for="plan in props.plans"
                    :key="plan.slug"
                    data-test="plan-option"
                    class="flex flex-col gap-3 rounded-lg border p-4"
                    :class="{
                        'border-primary': plan.slug === props.subscription.plan,
                    }"
                >
                    <div class="flex items-center justify-between">
                        <span class="font-medium">{{ plan.name }}</span>
                        <Badge
                            v-if="plan.slug === props.subscription.plan"
                            variant="secondary"
                            >Current</Badge
                        >
                    </div>
                    <div class="text-xl font-semibold">
                        ZMW {{ plan.price }}
                        <span class="text-sm font-normal text-muted-foreground"
                            >/month</span
                        >
                    </div>
                    <ul class="text-sm text-muted-foreground">
                        <li v-for="feature in plan.features" :key="feature">
                            {{ feature }}
                        </li>
                    </ul>
                    <Button
                        type="submit"
                        name="plan"
                        :value="plan.slug"
                        class="mt-auto"
                        :variant="plan.isUpgrade ? 'default' : 'outline'"
                        :disabled="processing || isDisabled(plan)"
                    >
                        {{ actionLabel(plan) }}
                    </Button>
                </div>
            </div>
        </Form>
    </div>
</template>
