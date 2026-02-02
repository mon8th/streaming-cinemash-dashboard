<script setup>
import { Link } from '@inertiajs/vue3';
import cineMash from '@/Components/icons/cineMash.vue';
import dashboard from '@/Components/icons/dashBoard.vue';
import streaming from '@/Components/icons/streaming.vue';
import profile from '@/Components/icons/profile.vue';
</script>

<template>
    <aside class="w-64 bg-slate-800 min-h-screen border-r border-slate-700">
        <div class="p-4 border-b border-slate-700">
            <Link href="/">
            <div class="flex items-center gap-3">
                <div>
                    <cineMash />
                </div>
                <span class="text-lg font-bold text-white">CineMash</span>
            </div>
            </Link>
        </div>

        <nav class="p-4 space-y-1">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4 px-3 mt-2">Dashboards</div>
            <Link href="/" :class="$page.url === '/' ? 'flex items-center gap-3 px-3 py-2.5 text-purple-400 bg-purple-500/15 rounded-lg transition-colors' : 'flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-700 hover:text-white rounded-lg transition-colors'">
                <div class="w-5 h-5 shrink-0">
                    <dashboard />
                </div>
                <span class="font-medium">Dashboard</span>
            </Link>
            <Link href="/streaming"
                :class="$page.url === '/streaming' ? 'flex items-center gap-3 px-3 py-2.5 text-purple-400 bg-purple-500/15 rounded-lg transition-colors' : 'flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-700 hover:text-white rounded-lg transition-colors'">
                <div class="w-5 h-5 shrink-0">
                    <streaming />
                </div>
                <span class="font-medium">Streaming</span>
            </Link>
            <Link href="/profile" :class="$page.url === '/profile' ? 'flex items-center gap-3 px-3 py-2.5 text-purple-400 bg-purple-500/15 rounded-lg transition-colors' : 'flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-700 hover:text-white rounded-lg transition-colors'">
                <div class="w-5 h-5 shrink-0">
                    <profile />
                </div>
                <span class="font-medium">Profile</span>
            </Link>
        </nav>
    </aside>
</template>
