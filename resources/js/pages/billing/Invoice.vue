<script setup lang="ts">
import { Head, router, useHttp, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { store, verify } from '@/routes/invoices/payments';
import type { Invoice, LencoCheckout, Team } from '@/types';

type Props = {
    invoice: Invoice;
    canPay: boolean;
    widgetUrl: string;
    status?: string;
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
            { title: 'Invoice', href: '#' },
        ],
    }),
});

const page = usePage();
const team = computed(() => page.props.currentTeam as Team);

const statusMessages: Record<string, string> = {
    'payment-successful':
        "Payment received, thank you. We're setting up your hosting and will email your login details shortly.",
    'payment-pending':
        "We're waiting for your payment to be confirmed. If you approved it on your phone, this page will update once it clears.",
    'payment-failed':
        "Your payment didn't go through. You can try again below.",
};

const widgetReady = ref(false);
const widgetError = ref<string | null>(null);

const http = useHttp<Record<string, never>, LencoCheckout>(
    () => store({ current_team: team.value.slug, invoice: props.invoice.id }),
    {},
);

onMounted(() => {
    if (!props.canPay) {
        return;
    }

    if (window.LencoPay) {
        widgetReady.value = true;

        return;
    }

    const script = document.createElement('script');
    script.src = props.widgetUrl;
    script.onload = () => (widgetReady.value = true);
    script.onerror = () =>
        (widgetError.value =
            'The payment window could not be loaded. Please refresh and try again.');
    document.head.appendChild(script);
});

function confirm(reference: string): void {
    router.post(
        verify({
            current_team: team.value.slug,
            invoice: props.invoice.id,
            payment: reference,
        }),
    );
}

async function pay(): Promise<void> {
    widgetError.value = null;

    const checkout = await http.submit();

    window.LencoPay?.getPaid({
        ...checkout,
        channels: ['mobile-money', 'card'],
        onSuccess: () => confirm(checkout.reference),
        onConfirmationPending: () => confirm(checkout.reference),
        onClose: () => {},
    });
}
</script>

<template>
    <Head :title="`Invoice ${props.invoice.number}`" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <Heading
                :title="`Invoice ${props.invoice.number}`"
                :description="`Due ${props.invoice.dueAt}`"
            />
            <Badge
                :variant="
                    props.invoice.status === 'paid' ? 'default' : 'secondary'
                "
            >
                {{ props.invoice.statusLabel }}
            </Badge>
        </div>

        <Alert v-if="props.status && statusMessages[props.status]">
            <AlertDescription>{{
                statusMessages[props.status]
            }}</AlertDescription>
        </Alert>

        <div class="flex items-center justify-between rounded-lg border p-4">
            <span>{{ props.invoice.description }}</span>
            <span class="font-semibold">ZMW {{ props.invoice.amount }}</span>
        </div>

        <div v-if="props.canPay" class="space-y-2">
            <Button
                :disabled="!widgetReady || http.processing"
                data-test="pay-invoice-button"
                @click="pay"
            >
                Pay ZMW {{ props.invoice.amount }}
            </Button>
            <p class="text-sm text-muted-foreground">
                Pay securely with mobile money or card through Lenco.
            </p>
            <InputError :message="widgetError ?? undefined" />
        </div>

        <p
            v-else-if="props.invoice.paidAt"
            class="text-sm text-muted-foreground"
        >
            Paid on {{ props.invoice.paidAt }}.
        </p>
    </div>
</template>
