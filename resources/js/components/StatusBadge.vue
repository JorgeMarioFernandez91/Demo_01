<script setup>
defineProps({
    status: { type: String, required: true },
});

const styles = {
    open: { badge: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', dot: 'bg-emerald-500' },
    pending: { badge: 'bg-amber-50 text-amber-700 ring-amber-600/20', dot: 'bg-amber-500' },
    closed: { badge: 'bg-slate-100 text-slate-600 ring-slate-500/20', dot: 'bg-slate-400' },
};
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium capitalize ring-1 ring-inset',
            styles[status]?.badge,
        ]"
    >
        <span :class="['size-1.5 rounded-full', styles[status]?.dot]" />
        {{ status }}
    </span>
</template>
