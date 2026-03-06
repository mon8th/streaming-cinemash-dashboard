<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const movies = ref([])
const loading = ref(false)
const selected = ref(null)

onMounted(async () => {
    loading.value = true
    const res = await axios.get('/api/movies')
    movies.value = res.data.data
    loading.value = false
})

function getPlatformLink(provider, title) {
    const q = encodeURIComponent(title)
    const map = {
        'Netflix': `https://www.netflix.com/search?q=${q}`,
        'Disney Plus': `https://www.disneyplus.com/search/${q}`,
        'HBO Max': `https://www.hbomax.com/search?q=${q}`,
        'Amazon Prime Video': `https://www.amazon.com/s?k=${q}`,
        'Apple TV Plus': `https://tv.apple.com/search?term=${q}`,
        'Hulu': `https://www.hulu.com/search?q=${q}`,
        'Paramount Plus': `https://www.paramountplus.com/search/${q}/`,
        'YouTube': `https://www.youtube.com/results?search_query=${q}`,
        'Google Play Movies': `https://play.google.com/store/search?q=${q}&c=movies`,
    }
    return map[provider]
}
</script>

<template>
    <div>
        <h1 class="text-3xl font-bold text-white mb-6">Streaming Dashboard</h1>

        <div v-if="loading" class="text-slate-400">Loading movies...</div>

        <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div v-for="movie in movies" :key="movie.id"
                class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden cursor-pointer hover:border-slate-500 transition"
                @click="selected = movie">
                <img :src="movie.poster_path" :alt="movie.title" class="w-full object-cover" />
                <div class="p-4">
                    <h2 class="text-white font-semibold text-sm mb-1">{{ movie.title }}</h2>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="selected" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-6"
            @click.self="selected = null">
            <div class="bg-slate-800 rounded-2xl max-w-2xl w-full overflow-hidden">
                <div class="relative">
                    <img :src="selected.backdrop_path" class="w-full h-48 object-cover" />
                    <button @click="selected = null"
                        class="absolute top-3 right-3 bg-black/50 text-white rounded-full w-8 h-8">✕</button>
                </div>
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-white mb-1">{{ selected.title }}</h2>
                    <p class="text-slate-300 text-sm mb-6">{{ selected.overview }}</p>

                    <div class="mb-4">
                        <div class="text-xs text-slate-400 uppercase mb-3">Where to Watch</div>
                        <div v-if="selected.streaming_providers.length">
                            <div v-for="type in ['flatrate', 'rent', 'buy']" :key="type">
                                <div v-if="selected.streaming_providers.filter(p => p.type === type).length"
                                    class="mb-3">
                                    <div class="text-xs text-slate-500 mb-2 capitalize">{{ type === 'flatrate' ? 'Stream' : type }}</div>
                                    <div class="flex flex-wrap gap-2">
                                        <a v-for="p in selected.streaming_providers.filter(p => p.type === type)"
                                            :key="p.id" :href="getPlatformLink(p.provider_name, selected.title)"
                                            target="_blank"
                                            class="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 rounded-lg px-3 py-2 transition">
                                            <img :src="`https://image.tmdb.org/t/p/w45${p.logo_path}`"
                                                class="w-6 h-6 rounded" />
                                            <span class="text-sm text-white">{{ p.provider_name }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-slate-500 text-sm">Not available</div>
                    </div>

                    <a v-if="selected.trailer_url" :href="selected.trailer_url" target="_blank"
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        ▶ Watch Trailer
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
