<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';
import StatusBadge from '../../components/StatusBadge.vue';

defineProps({
    ticket: { type: Object, required: true },
});
</script>

<template>
    <Head :title="ticket.subject" />
    <AppLayout>
        <Link href="/tickets" class="text-sm text-blue-600 hover:underline">&larr; All tickets</Link>
        <article class="mt-4 rounded border border-gray-200 bg-white p-6">
            <h1 class="text-2xl font-semibold">{{ ticket.subject }}</h1>
            <div class="mt-2 flex items-center gap-3 text-sm text-gray-600">
                <StatusBadge :status="ticket.status" />
                <span>{{ ticket.category ?? 'Uncategorized' }}</span>
                <span>{{ new Date(ticket.created_at).toLocaleString() }}</span>
            </div>
            <p class="mt-6 whitespace-pre-line">{{ ticket.body }}</p>
        </article>
    </AppLayout>
</template>
