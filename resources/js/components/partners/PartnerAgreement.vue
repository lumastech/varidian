<script setup lang="ts">
const props = defineProps<{
    partnerName?: string;
    nrcNumber?: string;
    effectiveFrom?: string;
}>();

const blank = '________________';
</script>

<template>
    <div class="space-y-3 text-sm leading-relaxed">
        <p>
            This agreement is made between
            <strong>Varidian Consulting Limited</strong> of Lusaka, Zambia
            ("Varidian") and <strong>{{ props.partnerName || blank }}</strong
            >, NRC No. <strong>{{ props.nrcNumber || blank }}</strong> ("the
            Partner"), effective from
            <strong>{{ props.effectiveFrom || 'the date it is signed' }}</strong
            >.
        </p>
        <p>
            <strong>1. Appointment.</strong> Varidian appoints the Partner as a
            non-exclusive, independent sales partner to promote Varidian's
            products and services in Zambia.
        </p>
        <p>
            <strong>2. Independent relationship.</strong> The Partner is not an
            employee, agent or director of Varidian. The Partner receives no
            salary or benefits, sets their own hours, and covers their own
            expenses unless Varidian agrees in writing beforehand.
        </p>
        <div>
            <p>
                <strong>3. Partner responsibilities.</strong> The Partner will:
            </p>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                <li>
                    represent Varidian honestly and describe its products
                    accurately;
                </li>
                <li>register every lead as set out in the programme terms;</li>
                <li>
                    not promise prices, discounts, features or delivery dates
                    without Varidian's written approval;
                </li>
                <li>
                    not sign contracts or collect money on Varidian's behalf.
                </li>
            </ul>
        </div>
        <p>
            <strong>4. Varidian responsibilities.</strong> Varidian will provide
            product information and pitch materials, support the Partner with
            demos and proposals, deliver the work it sells, and pay commission
            on time with a clear statement.
        </p>
        <p>
            <strong>5. Commission.</strong> Commission is paid according to the
            Commission Structure and Payment Terms of the programme. Varidian
            may change the rates with 30 days' written notice; changes do not
            affect clients already registered or signed.
        </p>
        <p>
            <strong>6. Confidentiality.</strong> The Partner will keep
            Varidian's pricing, client information, source code and business
            plans confidential, during this agreement and for 2 years after it
            ends. Client personal data must be handled in line with the Data
            Protection Act No. 3 of 2021.
        </p>
        <p>
            <strong>7. Brand use.</strong> The Partner may use the Varidian name
            and logo only to promote Varidian, and only in materials Varidian
            provides or approves.
        </p>
        <p>
            <strong>8. Term and termination.</strong> This agreement runs for 12
            months and renews automatically each year. Either party may end it
            with 30 days' written notice (WhatsApp or email counts). Varidian
            may end it immediately for dishonesty or a breach of
            confidentiality.
        </p>
        <p>
            <strong>9. After termination.</strong> The Partner is still paid
            commission on payments received for clients they signed before
            termination, for the remainder of the periods in the commission
            table. Renewal commission stops on termination.
        </p>
        <p>
            <strong>10. Governing law.</strong> This agreement is governed by
            the laws of the Republic of Zambia. The parties will first try to
            settle any disagreement by discussion.
        </p>
        <p class="text-muted-foreground">
            Signed for Varidian Consulting Limited by Lubomba Mulomya, Director.
        </p>
    </div>
</template>
