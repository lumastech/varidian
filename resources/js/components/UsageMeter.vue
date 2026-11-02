<script setup lang="ts">
import { computed } from 'vue';
import type { UsageMetric } from '@/types';

type Props = {
    label: string;
    metric: UsageMetric;
    unit?: 'mb' | 'count';
};

const props = withDefaults(defineProps<Props>(), { unit: 'count' });

function format(value: number): string {
    if (props.unit === 'count') {
        return String(value);
    }

    return value >= 1024 ? `${(value / 1024).toFixed(1)}GB` : `${value}MB`;
}

const percentage = computed(() =>
    props.metric.limit
        ? Math.min(
              100,
              Math.round((props.metric.used / props.metric.limit) * 100),
          )
        : 0,
);
</script>

<template>
    <div class="space-y-1">
        <div class="flex justify-between text-xs">
            <span class="text-muted-foreground">{{ label }}</span>
            <span>
                {{ format(metric.used) }} /
                {{ metric.limit === null ? 'Unlimited' : format(metric.limit) }}
            </span>
        </div>
        <div class="h-1.5 overflow-hidden rounded-full bg-muted">
            <div
                class="h-full rounded-full"
                :class="percentage >= 90 ? 'bg-destructive' : 'bg-primary'"
                :style="{ width: `${percentage}%` }"
            />
        </div>
    </div>
</template>
