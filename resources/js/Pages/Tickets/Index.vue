<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import StatusBadge from '../../Components/StatusBadge.vue';

defineProps({
    tickets: { type: Array, required: true },
});
</script>

<template>
    <Head title="Tickets" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold">Tickets</h1>
        <p v-if="tickets.length === 0" class="text-gray-500">No tickets yet.</p>
        <table v-else class="w-full overflow-hidden rounded border border-gray-200 bg-white text-sm">
            <thead class="bg-gray-100 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-2">Subject</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="ticket in tickets" :key="ticket.id" class="border-t border-gray-200">
                    <td class="px-4 py-2">
                        <Link :href="`/tickets/${ticket.id}`" class="text-blue-600 hover:underline">
                            {{ ticket.subject }}
                        </Link>
                    </td>
                    <td class="px-4 py-2 text-gray-600">{{ ticket.category ?? '—' }}</td>
                    <td class="px-4 py-2"><StatusBadge :status="ticket.status" /></td>
                </tr>
            </tbody>
        </table>
    </AppLayout>
</template>
