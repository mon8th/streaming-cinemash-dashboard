<script setup>
import sidebar from '@/Components/sidebar.vue';
import navbar from '@/Components/navbar.vue';
</script>

<template>
    <div class="flex min-h-screen bg-slate-900">
        <sidebar />
        <div class="flex-1 flex flex-col">
            <navbar />
            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
