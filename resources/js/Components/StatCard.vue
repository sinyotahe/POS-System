<script setup>
defineProps({
    title: { type: String, required: true },
    value: { type: [String, Number], default: '' },
    icon: { type: String, default: '' },
    colorClass: { type: String, default: 'bg-primary-50 text-primary-600' },
    trend: { type: String, default: '' },
    trendLabel: { type: String, default: '' },
})
</script>

<template>
    <div class="stat-card animate-fade-in">
        <div class="stat-icon" :class="colorClass">
            <svg v-if="icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icon"/>
            </svg>
        </div>
        <p class="text-xs font-medium uppercase tracking-wider text-slate-500">{{ title }}</p>
        <p class="mt-2 text-2xl font-bold text-slate-900">{{ value }}</p>
        <p v-if="trend" class="mt-1 text-sm" :class="trend.startsWith('+') ? 'text-emerald-600' : trend.startsWith('-') ? 'text-red-600' : 'text-slate-500'">
            {{ trend }} {{ trendLabel }}
        </p>
    </div>
</template>
