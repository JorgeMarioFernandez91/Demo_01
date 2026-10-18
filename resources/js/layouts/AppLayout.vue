<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import Avatar from '../components/Avatar.vue';
import Logo from '../components/Logo.vue';

const page = usePage();
</script>

<template>
    <div class="min-h-screen">
        <header class="sticky top-0 z-10 border-b border-slate-200 bg-white/80 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <Link href="/tickets" class="flex items-center gap-2.5">
                        <Logo />
                        <span class="text-base font-semibold tracking-tight">Helpdesk</span>
                    </Link>
                    <span
                        v-if="page.props.tenant"
                        class="hidden rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 sm:inline-flex"
                    >
                        {{ page.props.tenant.name }}
                    </span>
                </div>

                <div v-if="page.props.auth.user" class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-medium leading-tight">{{ page.props.auth.user.name }}</p>
                        <p class="text-xs text-slate-500">{{ page.props.auth.user.email }}</p>
                    </div>
                    <Avatar :name="page.props.auth.user.name" size="sm" />
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="rounded-lg px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                    >
                        Log out
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            <slot />
        </main>
    </div>
</template>
