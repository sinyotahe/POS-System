<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen">
        <!-- Left - Branding (hidden on mobile) -->
        <div class="hidden flex-1 flex-col items-center justify-center bg-gradient-to-br from-primary-600 to-primary-900 p-12 lg:flex">
            <div class="max-w-md text-center">
                <ApplicationLogo class="mx-auto h-16 w-16 fill-white" />
                <h1 class="mt-6 text-3xl font-bold text-white">POS Inventory</h1>
                <p class="mt-3 text-lg text-primary-200">Sistem Point of Sale & Inventory Management</p>
                <div class="mt-8 grid grid-cols-3 gap-6 text-center">
                    <div class="rounded-xl bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-2xl font-bold text-white">POS</p>
                        <p class="mt-1 text-xs text-primary-200">Transaksi Cepat</p>
                    </div>
                    <div class="rounded-xl bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-2xl font-bold text-white">Stok</p>
                        <p class="mt-1 text-xs text-primary-200">Inventory Real-time</p>
                    </div>
                    <div class="rounded-xl bg-white/10 p-4 backdrop-blur-sm">
                        <p class="text-2xl font-bold text-white">Lap.</p>
                        <p class="mt-1 text-xs text-primary-200">Analisis Bisnis</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right - Form -->
        <div class="flex flex-1 items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-sm animate-fade-in">
                <div class="mb-8 text-center lg:hidden">
                    <ApplicationLogo class="mx-auto h-12 w-12 fill-primary-600" />
                    <h2 class="mt-4 text-xl font-bold text-slate-900 dark:text-slate-100">POS Inventory</h2>
                </div>
                <div class="rounded-2xl border bg-white p-8 shadow-lg dark:border-slate-700 dark:bg-slate-800">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
