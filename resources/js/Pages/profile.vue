<script setup>
defineProps({
    user: String
})
</script>

<template>
    <div class="max-w-4xl">
        <h1 class="text-3xl font-bold text-white mb-6">Profile</h1>

        <div class="space-y-6">
            <!-- Profile Card -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                <div class="flex items-center gap-6 mb-6">
                    <div class="w-24 h-24 bg-purple-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                        {{ user ? user.charAt(0).toUpperCase() : 'U' }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-white mb-1">{{ user || 'User' }}</h2>
                        <p class="text-slate-400">{{ user }}@cinemash.com</p>
                    </div>
                    <button class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Account Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm text-slate-400 mb-1 block">Username</label>
                            <input type="text" :value="user || 'admin'" class="w-full bg-slate-900/50 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="text-sm text-slate-400 mb-1 block">Email</label>
                            <input type="email" :value="`${user || 'admin'}@cinemash.com`" class="w-full bg-slate-900/50 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="text-sm text-slate-400 mb-1 block">Role</label>
                            <input type="text" value="Administrator" class="w-full bg-slate-900/50 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-3 gap-6">
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <div class="text-sm text-slate-400 mb-2">Total Streams</div>
                    <div class="text-3xl font-bold text-white">127</div>
                    <div class="text-sm text-green-400 mt-2">+12% this month</div>
                </div>
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <div class="text-sm text-slate-400 mb-2">Total Views</div>
                    <div class="text-3xl font-bold text-white">45.2K</div>
                    <div class="text-sm text-green-400 mt-2">+18% this month</div>
                </div>
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <div class="text-sm text-slate-400 mb-2">Avg Duration</div>
                    <div class="text-3xl font-bold text-white">2.4h</div>
                    <div class="text-sm text-slate-400 mt-2">Per stream</div>
                </div>
            </div>

            <!-- Settings -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Settings</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        <div>
                            <div class="text-white font-medium">Email Notifications</div>
                            <div class="text-sm text-slate-400">Receive email updates about your streams</div>
                        </div>
                        <button class="w-12 h-6 bg-purple-500 rounded-full relative">
                            <div class="w-5 h-5 bg-white rounded-full absolute right-0.5 top-0.5"></div>
                        </button>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        <div>
                            <div class="text-white font-medium">Two-Factor Authentication</div>
                            <div class="text-sm text-slate-400">Add an extra layer of security</div>
                        </div>
                        <button class="w-12 h-6 bg-slate-600 rounded-full relative">
                            <div class="w-5 h-5 bg-white rounded-full absolute left-0.5 top-0.5"></div>
                        </button>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <div class="text-white font-medium">Auto-Archive Streams</div>
                            <div class="text-sm text-slate-400">Automatically save completed streams</div>
                        </div>
                        <button class="w-12 h-6 bg-purple-500 rounded-full relative">
                            <div class="w-5 h-5 bg-white rounded-full absolute right-0.5 top-0.5"></div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
