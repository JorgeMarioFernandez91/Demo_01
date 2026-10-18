<script setup>
import { categoryLabel } from '../lib/format';

defineProps({
    category: { type: String, default: null },
});

const styles = {
    billing: 'bg-violet-50 text-violet-700',
    account: 'bg-sky-50 text-sky-700',
    bug: 'bg-rose-50 text-rose-700',
    'feature-request': 'bg-indigo-50 text-indigo-700',
};
</script>

<template>
    <span :class="['inline-flex rounded-md px-2 py-0.5 text-xs font-medium', styles[category] ?? 'bg-slate-100 text-slate-500']">
        {{ categoryLabel(category) }}
    </span>
</template>
