<script setup>
import { initials } from '../lib/format';

defineProps({
    name: { type: String, required: true },
    size: { type: String, default: 'md' },
});

const sizes = {
    sm: 'size-8 text-xs',
    md: 'size-10 text-sm',
    lg: 'size-12 text-base',
};
</script>

<template>
    <span
        :class="[
            'inline-flex shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 font-semibold text-white',
            sizes[size],
        ]"
    >
        {{ initials(name) }}
    </span>
</template>
