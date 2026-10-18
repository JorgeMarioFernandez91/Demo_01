<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineProps({
    tenants: { type: Array, required: true },
});
</script>

<template>
    <Head title="Log in" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold">Log in as a demo tenant</h1>
        <div class="flex flex-col gap-2">
            <Link
                v-for="tenant in tenants"
                :key="tenant.id"
                :href="`/login/${tenant.id}`"
                method="post"
                as="button"
                class="rounded border border-gray-200 bg-white px-4 py-3 text-left hover:bg-gray-100"
            >
                {{ tenant.name }}
            </Link>
        </div>
    </AppLayout>
</template>
