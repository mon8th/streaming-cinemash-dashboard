<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import search from '@/Components/icons/search.vue';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <header class="h-15 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none">
                    <search />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <Link href="/profile" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-700 rounded-lg transition-colors">
                <span class="text-sm text-slate-300">{{ user?.name || 'Admin' }}</span>
                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ user?.name?.charAt(0).toUpperCase() || 'A' }}
                </div>
            </Link>
            <button @click="logout" class="px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                Logout
            </button>
        </div>
    </header>
</template>
