<script setup>
import search from '/public/icons/search.vue';
import settings from '/public/icons/settings.vue';
import notification from '/public/icons/notification.vue';
</script>

<template>
    <header class="h-16 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none">
                    <search />
                </div>
                <input type="text" placeholder="Search..." class="w-80 bg-slate-900/50 border border-slate-600 rounded-lg pl-10 pr-4 py-2 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button
                class="w-10 h-10 flex items-center justify-center text-slate-400 hover:bg-slate-700 hover:text-white rounded-lg transition-colors">
                <div class="w-5 h-5">
                    <settings />
                </div>
            </button>
            <button
                class="w-10 h-10 flex items-center justify-center text-slate-400 hover:bg-slate-700 hover:text-white rounded-lg relative transition-colors">
                <div class="w-5 h-5">
                    <notification />
                </div>
                <span class="absolute top-2 right-2 w-2 h-2 bg-purple-500 rounded-full"></span>
            </button>
            <img src="https://ui-avatars.com/api/?name=Admin&background=a855f7&color=fff" class="w-8 h-8 rounded-lg">
        </div>
    </header>
</template>
