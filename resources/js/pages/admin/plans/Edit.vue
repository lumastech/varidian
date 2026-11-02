<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import PlanController from '@/actions/App/Http/Controllers/Admin/PlanController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes/admin';
import { index } from '@/routes/admin/plans';

type PlanFields = {
    slug: string;
    name: string;
    audience: string | null;
    hestia_package: string;
    price: string;
    storage_gb: number;
    bandwidth_gb: number;
    websites: number;
    databases: number;
    cron_jobs: number;
    backups: number;
    is_featured: boolean;
    is_active: boolean;
    sort_order: number;
};

type Props = {
    plan: PlanFields | null;
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin', href: dashboard() },
            { title: 'Plans', href: index() },
        ],
    },
});

const limits: { name: keyof PlanFields; label: string }[] = [
    { name: 'storage_gb', label: 'Storage (GB)' },
    { name: 'bandwidth_gb', label: 'Bandwidth (GB)' },
    { name: 'websites', label: 'Websites' },
    { name: 'databases', label: 'Databases' },
    { name: 'cron_jobs', label: 'Cron jobs' },
    { name: 'backups', label: 'Backups kept' },
];

const formAction = props.plan
    ? PlanController.update.form(props.plan.slug)
    : PlanController.store.form();
</script>

<template>
    <Head :title="props.plan ? `Edit ${props.plan.name}` : 'New plan'" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6 p-4">
        <Heading
            :title="props.plan ? `Edit ${props.plan.name}` : 'New plan'"
            description="Limits here are what customers see. Keep them in step with the Hestia package, which is what the server enforces."
        />

        <Form
            v-bind="formAction"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        :default-value="props.plan?.name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div v-if="!props.plan" class="grid gap-2">
                    <Label for="slug">URL slug</Label>
                    <Input
                        id="slug"
                        name="slug"
                        required
                        placeholder="e.g. premium"
                    />
                    <InputError :message="errors.slug" />
                </div>

                <div class="grid gap-2 sm:col-span-2">
                    <Label for="audience">Who it's for</Label>
                    <Input
                        id="audience"
                        name="audience"
                        :default-value="props.plan?.audience ?? ''"
                        placeholder="For a small business website"
                    />
                    <InputError :message="errors.audience" />
                </div>

                <div class="grid gap-2">
                    <Label for="price">Monthly price (ZMW)</Label>
                    <Input
                        id="price"
                        name="price"
                        type="number"
                        min="1"
                        step="0.01"
                        required
                        :default-value="props.plan?.price"
                    />
                    <InputError :message="errors.price" />
                </div>

                <div class="grid gap-2">
                    <Label for="hestia_package">Hestia package</Label>
                    <Input
                        id="hestia_package"
                        name="hestia_package"
                        required
                        :default-value="props.plan?.hestia_package"
                    />
                    <InputError :message="errors.hestia_package" />
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div
                    v-for="limit in limits"
                    :key="limit.name"
                    class="grid gap-2"
                >
                    <Label :for="limit.name">{{ limit.label }}</Label>
                    <Input
                        :id="limit.name"
                        :name="limit.name"
                        type="number"
                        min="0"
                        required
                        :default-value="props.plan?.[limit.name] as number"
                    />
                    <InputError :message="errors[limit.name]" />
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="grid gap-2">
                    <Label for="sort_order">Display order</Label>
                    <Input
                        id="sort_order"
                        name="sort_order"
                        type="number"
                        min="0"
                        required
                        :default-value="props.plan?.sort_order ?? 0"
                    />
                    <InputError :message="errors.sort_order" />
                </div>

                <label class="flex items-center gap-2 text-sm sm:mt-6">
                    <input type="hidden" name="is_active" value="0" />
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        :checked="props.plan?.is_active ?? true"
                    />
                    On sale
                </label>

                <label class="flex items-center gap-2 text-sm sm:mt-6">
                    <input type="hidden" name="is_featured" value="0" />
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        :checked="props.plan?.is_featured ?? false"
                    />
                    Featured
                </label>
            </div>

            <Button type="submit" :disabled="processing">
                {{ props.plan ? 'Save changes' : 'Create plan' }}
            </Button>
        </Form>
    </div>
</template>
