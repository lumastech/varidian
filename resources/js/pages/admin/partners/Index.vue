<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import LeadReviewController from '@/actions/App/Http/Controllers/Admin/LeadReviewController';
import PartnerController from '@/actions/App/Http/Controllers/Admin/PartnerController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { dashboard } from '@/routes/admin';
import { index, show } from '@/routes/admin/partners';
import type { PartnerLead, PartnerStatus } from '@/types';

type AdminPartner = {
    id: number;
    fullName: string;
    email: string;
    phone: string;
    status: PartnerStatus;
    statusLabel: string;
    appliedAt: string;
    leads: number;
    wonLeads: number;
    due: string;
};

type Props = {
    partners: AdminPartner[];
    leadsToReview: (PartnerLead & {
        partner: string;
        partnerId: number;
        conflicts: string[];
    })[];
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

const applications = computed(() =>
    props.partners.filter((partner) => partner.status === 'pending'),
);
const enrolled = computed(() =>
    props.partners.filter((partner) => partner.status !== 'pending'),
);
</script>

<template>
    <Head title="Sales partners" />

    <div class="flex flex-col gap-8 p-4">
        <section class="space-y-4">
            <Heading
                variant="small"
                title="Leads to confirm"
                description="Confirm within 2 working days, or tell the partner it's already registered or an existing client"
            />

            <p
                v-if="leadsToReview.length === 0"
                class="text-sm text-muted-foreground"
            >
                Nothing waiting.
            </p>

            <div
                v-for="lead in leadsToReview"
                :key="lead.id"
                data-test="lead-to-review"
                class="space-y-3 rounded-lg border p-4 text-sm"
            >
                <div>
                    <div class="font-medium">
                        {{ lead.organisation }} · {{ lead.interest }}
                    </div>
                    <div class="text-muted-foreground">
                        {{ lead.contactName }} · {{ lead.contactPhone }}
                        <template v-if="lead.contactEmail">
                            · {{ lead.contactEmail }}</template
                        >
                        · from
                        <Link
                            :href="show(lead.partnerId)"
                            class="underline underline-offset-4"
                            >{{ lead.partner }}</Link
                        >
                        on {{ lead.registeredAt }}
                    </div>
                    <p v-if="lead.notes" class="mt-1 whitespace-pre-line">
                        {{ lead.notes }}
                    </p>
                </div>

                <div v-if="lead.conflicts.length" class="flex flex-wrap gap-2">
                    <Badge
                        v-for="conflict in lead.conflicts"
                        :key="conflict"
                        variant="destructive"
                    >
                        {{ conflict }}
                    </Badge>
                </div>

                <div class="flex flex-wrap items-start gap-2">
                    <Form
                        v-bind="LeadReviewController.form(lead.id)"
                        :options="{ preserveScroll: true }"
                        v-slot="{ processing }"
                    >
                        <input type="hidden" name="decision" value="confirm" />
                        <Button size="sm" type="submit" :disabled="processing"
                            >Confirm</Button
                        >
                    </Form>
                    <Form
                        v-bind="LeadReviewController.form(lead.id)"
                        class="flex flex-wrap items-start gap-2"
                        :options="{ preserveScroll: true }"
                        v-slot="{ errors, processing }"
                    >
                        <input type="hidden" name="decision" value="reject" />
                        <div>
                            <Input
                                name="note"
                                placeholder="Reason, e.g. existing client"
                                class="h-8 w-64"
                            />
                            <InputError :message="errors.note" />
                        </div>
                        <Button
                            size="sm"
                            variant="outline"
                            type="submit"
                            :disabled="processing"
                            >Reject</Button
                        >
                    </Form>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <Heading variant="small" title="Applications" />

            <p
                v-if="applications.length === 0"
                class="text-sm text-muted-foreground"
            >
                No new applications.
            </p>

            <div
                v-for="partner in applications"
                :key="partner.id"
                class="flex flex-wrap items-center justify-between gap-4 rounded-lg border p-4 text-sm"
            >
                <div>
                    <Link
                        :href="show(partner.id)"
                        class="font-medium underline-offset-4 hover:underline"
                    >
                        {{ partner.fullName }}
                    </Link>
                    <div class="text-muted-foreground">
                        {{ partner.email }} · {{ partner.phone }} · applied
                        {{ partner.appliedAt }}
                    </div>
                </div>
                <Form
                    v-bind="PartnerController.updateStatus.form(partner.id)"
                    :options="{ preserveScroll: true }"
                    v-slot="{ processing }"
                >
                    <input type="hidden" name="status" value="active" />
                    <Button size="sm" type="submit" :disabled="processing"
                        >Approve</Button
                    >
                </Form>
            </div>
        </section>

        <section class="space-y-4">
            <Heading variant="small" title="Partners" />

            <div class="overflow-x-auto rounded-lg border">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50 text-muted-foreground">
                        <tr>
                            <th class="p-3 font-medium">Partner</th>
                            <th class="p-3 font-medium">Status</th>
                            <th class="p-3 text-right font-medium">Leads</th>
                            <th class="p-3 text-right font-medium">Signed</th>
                            <th class="p-3 text-right font-medium">
                                Due (ZMW)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-if="enrolled.length === 0">
                            <td colspan="5" class="p-3 text-muted-foreground">
                                No partners yet.
                            </td>
                        </tr>
                        <tr v-for="partner in enrolled" :key="partner.id">
                            <td class="p-3">
                                <Link
                                    :href="show(partner.id)"
                                    class="font-medium underline-offset-4 hover:underline"
                                >
                                    {{ partner.fullName }}
                                </Link>
                                <div class="text-muted-foreground">
                                    {{ partner.phone }}
                                </div>
                            </td>
                            <td class="p-3">
                                <Badge
                                    :variant="
                                        partner.status === 'active'
                                            ? 'default'
                                            : 'secondary'
                                    "
                                >
                                    {{ partner.statusLabel }}
                                </Badge>
                            </td>
                            <td class="p-3 text-right">{{ partner.leads }}</td>
                            <td class="p-3 text-right">
                                {{ partner.wonLeads }}
                            </td>
                            <td class="p-3 text-right font-medium">
                                {{ partner.due }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>
