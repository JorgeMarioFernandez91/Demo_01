<script setup>
defineProps({
    status: { type: String, required: true },
});

const classes = {
    open: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    closed: 'bg-gray-200 text-gray-700',
};
</script>

<template>
    <span :class="['rounded px-2 py-0.5 text-xs font-medium', classes[status]]">{{ status }}</span>
</template>
