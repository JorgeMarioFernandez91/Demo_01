<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div class="mx-auto max-w-4xl px-4 py-8">
        <header class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
            <Link href="/tickets" class="text-lg font-semibold">
                Helpdesk
                <span v-if="page.props.tenant" class="font-normal text-gray-500">· {{ page.props.tenant.name }}</span>
            </Link>
            <div v-if="page.props.auth.user" class="flex items-center gap-3 text-sm text-gray-600">
                <span>{{ page.props.auth.user.name }}</span>
                <Link href="/logout" method="post" as="button" class="text-blue-600 hover:underline">Log out</Link>
            </div>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>
