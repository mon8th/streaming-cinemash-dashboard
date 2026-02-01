<script setup>
import { Link } from '@inertiajs/vue3';
import cineMash from '/public/icons/cineMash.vue';
import dashboard from '/public/icons/dashBoard.vue';
import streaming from '/public/icons/streaming.vue';
</script>

<template>
    <aside class="w-64 bg-slate-800 min-h-screen border-r border-slate-700">
        <div class="p-5 border-b border-slate-700">
            <div class="flex items-center gap-3">
                <div>
                    <cineMash />
                </div>
                <span class="text-lg font-bold text-white">CineMash</span>
            </div>
        </div>

        <nav class="p-4">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">Dashboards</div>
            <Link href="/" class="flex items-center gap-3 px-3 py-2.5 text-purple-400 bg-purple-500/15 rounded-lg mb-1">
                <div class="w-5 h-5">
                    <dashboard />
                </div>
                <span>Dashboard</span>
            </Link>
            <Link href="/streaming"
                class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-700 hover:text-white rounded-lg">
                <div class="w-5 h-5">
                    <streaming />
                </div>
                <span>Streaming</span>
            </Link>
        </nav>
    </aside>
</template>
