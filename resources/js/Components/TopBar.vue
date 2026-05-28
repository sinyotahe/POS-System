<script setup>
import { Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useDarkMode } from '@/composables/useDarkMode'

const props = defineProps({
    branches: { type: Array, default: () => [] },
    activeBranch: { type: Object, default: null },
    user: { type: Object, required: true },
})

const emit = defineEmits(['toggle-sidebar'])

const { isDark, toggle } = useDarkMode()

const switchBranch = (branchId) => {
    router.post(route('branch.switch'), { branch_id: branchId }, {
        preserveState: true,
        preserveScroll: true,
    })
}
</script>

<template>
    <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b bg-white/80 px-4 backdrop-blur-md sm:px-6">
        <div class="flex items-center gap-3">
            <button @click="emit('toggle-sidebar')" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 lg:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div v-if="branches.length > 1">
                <select :value="activeBranch?.id" @change="switchBranch($event.target.value)" class="rounded-lg border-slate-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option v-for="b in branches" :key="b.id" :value="b.id">{{ b.name }}</option>
                </select>
            </div>
            <div v-else-if="activeBranch" class="flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                {{ activeBranch.name }}
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button @click="toggle" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 dark:text-slate-500 dark:hover:bg-slate-700 dark:hover:text-slate-300" :title="isDark ? 'Mode Terang' : 'Mode Gelap'">
                <svg v-if="isDark" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>
            <div class="relative">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-50 text-sm font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-400">
                    {{ user.name.charAt(0).toUpperCase() }}
                </span>
            </div>
        </div>
    </header>
</template>
