<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import CategoryBadge from '../../components/CategoryBadge.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import AppLayout from '../../layouts/AppLayout.vue';
import { categoryLabel, timeAgo } from '../../lib/format';

const props = defineProps({
    tickets: { type: Array, required: true },
});

const search = ref('');
const status = ref('all');
const category = ref('all');

const statusTabs = computed(() => [
    { key: 'all', label: 'All tickets', count: props.tickets.length },
    ...['open', 'pending', 'closed'].map((key) => ({
        key,
        label: key[0].toUpperCase() + key.slice(1),
        count: props.tickets.filter((ticket) => ticket.status === key).length,
    })),
]);

const categories = computed(() => [...new Set(props.tickets.map((ticket) => ticket.category))]);

const filtered = computed(() => {
    const term = search.value.trim().toLowerCase();

    return props.tickets.filter(
        (ticket) =>
            (status.value === 'all' || ticket.status === status.value) &&
            (category.value === 'all' || (ticket.category ?? 'none') === category.value) &&
            (!term || ticket.subject.toLowerCase().includes(term) || ticket.excerpt.toLowerCase().includes(term)),
    );
});

function reset() {
    search.value = '';
    status.value = 'all';
    category.value = 'all';
}
</script>

<template>
    <Head title="Tickets" />
    <AppLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight">Tickets</h1>
            <p class="mt-1 text-sm text-slate-500">Support requests from your customers.</p>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <button
                v-for="tab in statusTabs"
                :key="tab.key"
                type="button"
                :class="[
                    'rounded-xl border bg-white p-4 text-left shadow-sm transition',
                    status === tab.key ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-slate-200 hover:border-slate-300',
                ]"
                @click="status = tab.key"
            >
                <p class="text-sm font-medium text-slate-500">{{ tab.label }}</p>
                <p class="mt-1 text-2xl font-semibold tabular-nums">{{ tab.count }}</p>
            </button>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 p-4 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <svg
                        class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-slate-400"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Search tickets…"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pr-3 pl-9 text-sm placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                    />
                </div>
                <select
                    v-model="category"
                    class="rounded-lg border border-slate-200 bg-slate-50 py-2 pr-8 pl-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
                >
                    <option value="all">All categories</option>
                    <option v-for="c in categories" :key="c ?? 'none'" :value="c ?? 'none'">{{ categoryLabel(c) }}</option>
                </select>
            </div>

            <ul v-if="filtered.length" class="divide-y divide-slate-100">
                <li v-for="ticket in filtered" :key="ticket.id">
                    <Link
                        :href="`/tickets/${ticket.id}`"
                        class="group flex flex-col gap-2 px-4 py-4 transition hover:bg-slate-50 sm:flex-row sm:items-center sm:gap-6"
                    >
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-slate-400 tabular-nums">#{{ ticket.id }}</span>
                                <p class="truncate font-medium group-hover:text-indigo-600">{{ ticket.subject }}</p>
                            </div>
                            <p class="mt-0.5 truncate text-sm text-slate-500">{{ ticket.excerpt }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <CategoryBadge :category="ticket.category" />
                            <StatusBadge :status="ticket.status" />
                            <span class="w-24 text-right text-xs text-slate-400">{{ timeAgo(ticket.created_at) }}</span>
                        </div>
                    </Link>
                </li>
            </ul>

            <div v-else class="flex flex-col items-center px-4 py-16 text-center">
                <p class="font-medium">No tickets found</p>
                <p class="mt-1 text-sm text-slate-500">
                    {{ tickets.length ? 'Try a different search or filter.' : 'New support requests will show up here.' }}
                </p>
                <button
                    v-if="tickets.length"
                    type="button"
                    class="mt-4 rounded-lg bg-white px-3 py-1.5 text-sm font-medium text-indigo-600 ring-1 ring-slate-200 hover:bg-slate-50"
                    @click="reset"
                >
                    Clear filters
                </button>
            </div>
        </div>
    </AppLayout>
</template>
