<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CategoryBadge from '../../components/CategoryBadge.vue';
import StatusBadge from '../../components/StatusBadge.vue';
import AppLayout from '../../layouts/AppLayout.vue';
import { formatDate, timeAgo } from '../../lib/format';

defineProps({
    ticket: { type: Object, required: true },
});
</script>

<template>
    <Head :title="ticket.subject" />
    <AppLayout>
        <nav class="mb-4 flex items-center gap-2 text-sm text-slate-500">
            <Link href="/tickets" class="hover:text-slate-900">Tickets</Link>
            <span class="text-slate-300">/</span>
            <span class="text-slate-900 tabular-nums">#{{ ticket.id }}</span>
        </nav>

        <div class="grid items-start gap-6 lg:grid-cols-3">
            <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <div class="flex flex-wrap items-center gap-2">
                    <StatusBadge :status="ticket.status" />
                    <CategoryBadge :category="ticket.category" />
                </div>
                <h1 class="mt-3 text-2xl font-semibold tracking-tight">{{ ticket.subject }}</h1>
                <p class="mt-1 text-sm text-slate-500">Opened {{ timeAgo(ticket.created_at) }}</p>
                <div class="mt-6 border-t border-slate-100 pt-6 leading-relaxed whitespace-pre-line text-slate-700">
                    {{ ticket.body }}
                </div>
            </article>

            <aside class="h-fit rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold">Details</h2>
                <dl class="mt-4 space-y-4 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Ticket</dt>
                        <dd class="font-medium tabular-nums">#{{ ticket.id }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Status</dt>
                        <dd><StatusBadge :status="ticket.status" /></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Category</dt>
                        <dd><CategoryBadge :category="ticket.category" /></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Created</dt>
                        <dd class="font-medium">{{ formatDate(ticket.created_at) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Last updated</dt>
                        <dd class="font-medium">{{ formatDate(ticket.updated_at) }}</dd>
                    </div>
                </dl>
                <Link
                    href="/tickets"
                    class="mt-6 flex w-full items-center justify-center rounded-lg bg-white px-3 py-2 text-sm font-medium text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50"
                >
                    &larr; Back to all tickets
                </Link>
            </aside>
        </div>
    </AppLayout>
</template>
