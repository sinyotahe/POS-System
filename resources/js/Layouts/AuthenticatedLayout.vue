<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import SidebarNav from '@/Components/SidebarNav.vue'
import TopBar from '@/Components/TopBar.vue'

const sidebarOpen = ref(false)
const showingNavigationDropdown = ref(false)

const switchBranch = (branchId) => {
    router.post(route('branch.switch'), { branch_id: branchId }, {
        preserveState: true,
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="flex min-h-screen">
        <!-- Mobile overlay -->
        <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="[
            'fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-white border-r border-slate-200 transition-transform duration-300 lg:static lg:translate-x-0 dark:bg-slate-800 dark:border-slate-700',
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        ]">
            <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-4 dark:border-slate-700">
                <ApplicationLogo class="h-8 w-auto dark:fill-white" />
                <span class="text-lg font-bold text-slate-900 dark:text-slate-100">POS Inventory</span>
            </div>
            <SidebarNav :user="$page.props.auth.user" />
        </aside>

        <!-- Main -->
        <div class="flex flex-1 flex-col">
            <TopBar
                :branches="$page.props.branches ?? []"
                :activeBranch="$page.props.active_branch"
                :user="$page.props.auth.user"
                @toggle-sidebar="sidebarOpen = !sidebarOpen"
            />

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <!-- Header Slot -->
                <div v-if="$slots.header" class="mb-4">
                    <slot name="header" />
                </div>

                <!-- Flash Messages -->
                <div v-if="$page.props.flash?.success" class="mb-4 animate-slide-up rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 flex items-center gap-2 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash?.error" class="mb-4 animate-slide-up rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 flex items-center gap-2 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $page.props.flash.error }}
                </div>

                <slot />
            </main>

            <footer class="border-t border-slate-200 px-6 py-3 text-center text-xs text-slate-400 dark:border-slate-700 dark:text-slate-500">
                POS Inventory &copy; {{ new Date().getFullYear() }}
            </footer>
        </div>
    </div>
</template>
