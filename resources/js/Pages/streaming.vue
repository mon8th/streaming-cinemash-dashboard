<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const movies = ref([])
const loading = ref(false)
const selected = ref(null)
const editingLink = ref(null)
const editLinkValue = ref('')
const showAddForm = ref(false)
const newProvider = ref({
    provider_name: '',
    link: '',
    type: 'flatrate'
})

onMounted(async () => {
    loading.value = true
    const res = await axios.get('/api/movies')
    movies.value = res.data.data
    loading.value = false
})

function getPlatformLink(provider, title) {
    // Use custom link if exists, otherwise generate default
    if (provider.link) return provider.link

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
    return map[provider.provider_name]
}

function startEditLink(provider) {
    editingLink.value = provider.id
    editLinkValue.value = provider.link || getPlatformLink(provider, selected.value.title)
}

async function saveLink(provider) {
    try {
        const response = await axios.put(`/api/streaming-providers/${provider.id}`, {
            link: editLinkValue.value
        })

        // Update in both places to ensure reactivity
        provider.link = response.data.link

        // Also update in the movies array
        const movie = movies.value.find(m => m.id === selected.value.id)
        if (movie) {
            const providerInMovies = movie.streaming_providers.find(p => p.id === provider.id)
            if (providerInMovies) {
                providerInMovies.link = response.data.link
            }
        }

        editingLink.value = null
    } catch (error) {
        console.error('Failed to save link:', error)
        alert('Failed to save link')
    }
}

function cancelEdit() {
    editingLink.value = null
    editLinkValue.value = ''
}

function openAddForm() {
    showAddForm.value = true
    newProvider.value = {
        provider_name: '',
        link: '',
        type: 'flatrate'
    }
}

async function saveNewProvider() {
    if (!newProvider.value.provider_name || !newProvider.value.link) {
        alert('Please fill in both provider name and link')
        return
    }

    try {
        const response = await axios.post(`/api/movies/${selected.value.id}/streaming-providers`, newProvider.value)
        selected.value.streaming_providers.push(response.data)
        showAddForm.value = false
    } catch (error) {
        console.error('Failed to add provider:', error)
        alert('Failed to add provider')
    }
}

function cancelAddForm() {
    showAddForm.value = false
}

async function deleteProvider(provider) {
    if (!confirm(`Delete ${provider.provider_name}?`)) return

    try {
        await axios.delete(`/api/streaming-providers/${provider.id}`)
        const index = selected.value.streaming_providers.findIndex(p => p.id === provider.id)
        if (index > -1) {
            selected.value.streaming_providers.splice(index, 1)
        }
    } catch (error) {
        console.error('Failed to delete provider:', error)
        alert('Failed to delete provider')
    }
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
            <div class="bg-slate-800 rounded-2xl max-w-2xl w-full overflow-hidden max-h-[90vh] flex flex-col">
                <div class="relative shrink-0">
                    <img :src="selected.backdrop_path" class="w-full h-48 object-cover" />
                    <button @click="selected = null"
                        class="absolute top-3 right-3 bg-black/50 text-white rounded-full w-8 h-8">✕</button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <h2 class="text-2xl font-bold text-white mb-1">{{ selected.title }}</h2>
                    <p class="text-slate-300 text-sm mb-6">{{ selected.overview }}</p>

                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-xs text-slate-400 uppercase">Where to Watch</div>
                            <button @click="openAddForm"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs transition">
                                + Add Provider
                            </button>
                        </div>

                        <!-- Add Provider Form -->
                        <div v-if="showAddForm" class="bg-slate-900 rounded-lg p-4 mb-3 border border-slate-600">
                            <div class="text-sm text-white mb-3 font-semibold">Add Streaming Provider</div>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Provider Name</label>
                                    <input v-model="newProvider.provider_name"
                                        type="text"
                                        class="w-full bg-slate-800 text-white text-sm px-3 py-2 rounded-lg border border-slate-600 focus:border-blue-500 outline-none"
                                        placeholder="e.g., Netflix, Hulu" />
                                </div>
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Link</label>
                                    <input v-model="newProvider.link"
                                        type="text"
                                        class="w-full bg-slate-800 text-white text-sm px-3 py-2 rounded-lg border border-slate-600 focus:border-blue-500 outline-none"
                                        placeholder="https://..." />
                                </div>
                                <div>
                                    <label class="text-xs text-slate-400 block mb-1">Type</label>
                                    <select v-model="newProvider.type"
                                        class="w-full bg-slate-800 text-white text-sm px-3 py-2 rounded-lg border border-slate-600 focus:border-blue-500 outline-none">
                                        <option value="flatrate">Stream</option>
                                        <option value="rent">Rent</option>
                                        <option value="buy">Buy</option>
                                    </select>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="saveNewProvider"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                        Add Provider
                                    </button>
                                    <button @click="cancelAddForm"
                                        class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm transition">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-if="selected.streaming_providers.length">
                            <div v-for="type in ['flatrate', 'rent', 'buy']" :key="type">
                                <div v-if="selected.streaming_providers.filter(p => p.type === type).length"
                                    class="mb-3">
                                    <div class="text-xs text-slate-500 mb-2 capitalize">{{ type === 'flatrate' ? 'Stream' : type }}</div>
                                    <div class="flex flex-col gap-2">
                                        <div v-for="p in selected.streaming_providers.filter(p => p.type === type)"
                                            :key="p.id" class="flex items-center gap-2">
                                            <a v-if="editingLink !== p.id"
                                                :href="getPlatformLink(p, selected.title)"
                                                target="_blank"
                                                class="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 rounded-lg px-3 py-2 transition flex-1">
                                                <img :src="`https://image.tmdb.org/t/p/w45${p.logo_path}`"
                                                    class="w-6 h-6 rounded" />
                                                <span class="text-sm text-white">{{ p.provider_name }}</span>
                                            </a>
                                            <div v-else class="flex items-center gap-2 flex-1">
                                                <img :src="`https://image.tmdb.org/t/p/w45${p.logo_path}`"
                                                    class="w-6 h-6 rounded" />
                                                <input v-model="editLinkValue"
                                                    type="text"
                                                    class="flex-1 bg-slate-900 text-white text-sm px-3 py-2 rounded-lg border border-slate-600 focus:border-blue-500 outline-none"
                                                    placeholder="Enter link" />
                                            </div>
                                            <div v-if="editingLink !== p.id" class="flex gap-1">
                                                <button @click="startEditLink(p)"
                                                    class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-lg text-xs transition">
                                                    Edit
                                                </button>
                                                <button @click="deleteProvider(p)"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs transition">
                                                    Delete
                                                </button>
                                            </div>
                                            <div v-else class="flex gap-1">
                                                <button @click="saveLink(p)"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-xs transition">
                                                    Save
                                                </button>
                                                <button @click="cancelEdit"
                                                    class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-lg text-xs transition">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
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
