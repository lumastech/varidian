<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CheckoutController from '@/actions/App/Http/Controllers/Billing/CheckoutController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes';
import type { HostingPlan, Team } from '@/types';

type Props = {
    plan: HostingPlan;
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
            { title: 'Order hosting', href: '#' },
        ],
    }),
});

const page = usePage();
const team = computed(() => page.props.currentTeam as Team);
</script>

<template>
    <Head :title="`Order ${props.plan.name} hosting`" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6 p-4">
        <Heading
            :title="`${props.plan.name} hosting`"
            :description="props.plan.audience ?? undefined"
        />

        <div class="rounded-lg border p-4">
            <div class="text-2xl font-semibold">
                ZMW {{ props.plan.price }}
                <span class="text-sm font-normal text-muted-foreground"
                    >/month</span
                >
            </div>
            <ul
                class="mt-3 grid gap-1 text-sm text-muted-foreground sm:grid-cols-2"
            >
                <li v-for="feature in props.plan.features" :key="feature">
                    {{ feature }}
                </li>
            </ul>
        </div>

        <Form
            v-bind="
                CheckoutController.store.form({
                    current_team: team.slug,
                    plan: props.plan.slug,
                })
            "
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="domain">Your domain</Label>
                <Input
                    id="domain"
                    name="domain"
                    required
                    autocomplete="off"
                    placeholder="yourbusiness.co.zm"
                />
                <p class="text-sm text-muted-foreground">
                    The domain you'll point at this hosting. Don't have one yet?
                    Message us and we'll help you register it.
                </p>
                <InputError :message="errors.domain" />
            </div>

            <Button type="submit" :disabled="processing">
                Continue to payment
            </Button>
        </Form>
    </div>
</template>
