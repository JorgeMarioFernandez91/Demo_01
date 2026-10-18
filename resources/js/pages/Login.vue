<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Avatar from '../components/Avatar.vue';
import Logo from '../components/Logo.vue';

defineProps({
    tenants: { type: Array, required: true },
});
</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-violet-50 px-4">
        <div class="w-full max-w-md">
            <div class="mb-8 flex flex-col items-center text-center">
                <Logo />
                <h1 class="mt-4 text-2xl font-semibold tracking-tight">Welcome to Helpdesk</h1>
                <p class="mt-1 text-sm text-slate-500">Choose a demo tenant to sign in as its admin.</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-xl shadow-indigo-100/50">
                <Link
                    v-for="tenant in tenants"
                    :key="tenant.id"
                    :href="`/login/${tenant.id}`"
                    method="post"
                    as="button"
                    class="group flex w-full items-center gap-4 rounded-xl px-4 py-3 text-left transition hover:bg-slate-50"
                >
                    <Avatar :name="tenant.name" />
                    <span class="flex-1">
                        <span class="block font-medium">{{ tenant.name }}</span>
                        <span class="block text-sm text-slate-500">Sign in as admin</span>
                    </span>
                    <svg
                        class="size-5 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-indigo-500"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </Link>
            </div>

            <p class="mt-6 text-center text-xs text-slate-400">Demo login is only available in local development.</p>
        </div>
    </div>
</template>
