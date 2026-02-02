<script setup>
import Sidebar from '@/Components/Sidebar.vue';
import Navbar from '@/Components/Navbar.vue';
</script>

<template>
  <div class="flex h-screen bg-slate-900 overflow-hidden">
    <Sidebar />
    <div class="flex-1 flex flex-col min-w-0">
      <Navbar />
      <main class="flex-1 p-6 overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>
</template>
