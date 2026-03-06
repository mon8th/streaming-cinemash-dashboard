<script setup>
defineProps({
    stats: Object,
    latestMovies: Array
})
</script>

<template>
    <div>
        <h1 class="text-3xl font-bold text-white mb-6">Dashboard</h1>

        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-800 border border-slate-800/40 rounded-xl p-6">
                <div class="text-sm text-white mb-2">Total Movies</div>
                <div class="text-3xl font-bold text-white">{{ stats.totalMovies }}</div>
            </div>
            <div class="bg-slate-800 border border-slate-800/40 rounded-xl p-6">
                <div class="text-sm text-white mb-2">Added This Week</div>
                <div class="text-3xl font-bold text-white">{{ stats.recentlyAdded }}</div>
            </div>
            <div class="bg-slate-800 border border-s-800/40 rounded-xl p-6">
                <div class="text-sm text-white mb-2">Top Rated (8+)</div>
                <div class="text-3xl font-bold text-white">{{ stats.topRated }}</div>
            </div>
        </div>

        <h2 class="text-xl font-bold text-white mb-4">Latest Movies</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div v-for="movie in latestMovies" :key="movie.id"
                class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
                <img :src="movie.poster_path" :alt="movie.title" class="w-full aspect-2/3 object-cover" />
                <div class="p-3">
                    <h3 class="text-white font-semibold text-xs mb-1 truncate">{{ movie.title }}</h3>
                    <div class="text-slate-400 text-xs">⭐ {{ movie.vote_average }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
