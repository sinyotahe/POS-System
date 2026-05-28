<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch } from 'vue'

const props = defineProps({
    dateFrom: { type: String, default: '' },
    dateTo: { type: String, default: '' },
    omzet: { type: Number, default: 0 },
    grossProfit: { type: Number, default: 0 },
    estimatedProfit: { type: Number, default: 0 },
    costOfGoodsSold: { type: Number, default: 0 },
    dailyTotals: { type: Array, default: () => [] },
    totalSales: { type: Number, default: 0 },
    filters: { type: Object, default: () => ({ date_from: '', date_to: '' }) },
})

const dateFrom = ref(props.filters.date_from ?? props.dateFrom)
const dateTo = ref(props.filters.date_to ?? props.dateTo)

watch([dateFrom, dateTo], () => {
    router.get(route('reports.financial'), {
        date_from: dateFrom.value,
        date_to: dateTo.value,
    }, { preserveState: true, replace: true })
})

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`

const exportExcel = () => {
    const params = new URLSearchParams()
    if (dateFrom.value) params.set('date_from', dateFrom.value)
    if (dateTo.value) params.set('date_to', dateTo.value)
    window.open(route('reports.financial.export', 'xlsx') + '?' + params.toString(), '_blank')
}

const exportPdf = () => {
    const params = new URLSearchParams()
    if (dateFrom.value) params.set('date_from', dateFrom.value)
    if (dateTo.value) params.set('date_to', dateTo.value)
    window.open(route('reports.financial.export', 'pdf') + '?' + params.toString(), '_blank')
}
</script>

<template>
    <Head title="Laporan Keuangan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Laporan Keuangan</h2>
                <div class="flex gap-2">
                    <Link :href="route('reports.sales')" class="text-sm text-primary-600 hover:text-primary-800">Penjualan</Link>
                    <span class="text-slate-300">|</span>
                    <Link :href="route('reports.inventory')" class="text-sm text-primary-600 hover:text-primary-800">Inventory</Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="card p-4 mb-6 flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-xs text-slate-600">Dari</label>
                        <TextInput v-model="dateFrom" type="date" class="mt-1 w-44" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Sampai</label>
                        <TextInput v-model="dateTo" type="date" class="mt-1 w-44" />
                    </div>
                    <div class="flex gap-2">
                        <button @click="exportExcel" class="rounded-md bg-green-600 px-3 py-2 text-xs font-medium text-white hover:bg-green-500">Export Excel</button>
                        <button @click="exportPdf" class="rounded-md bg-red-600 px-3 py-2 text-xs font-medium text-white hover:bg-red-500">Export PDF</button>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mb-6 grid grid-cols-2 gap-4">
                    <div class="rounded-lg bg-white p-5 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Omzet</p>
                        <p class="mt-1 text-3xl font-bold text-slate-800">{{ formatPrice(omzet) }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-5 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Total Penjualan (sebelum pajak/diskon)</p>
                        <p class="mt-1 text-sm text-slate-600">{{ formatPrice(omzet) }}</p>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-4 gap-4">
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">HPP</p>
                        <p class="mt-1 text-lg font-bold text-slate-800">{{ formatPrice(costOfGoodsSold) }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Laba Kotor</p>
                        <p class="mt-1 text-lg font-bold text-green-600">{{ formatPrice(grossProfit) }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Laba Bersih (Estimasi)</p>
                        <p class="mt-1 text-lg font-bold text-primary-600">{{ formatPrice(estimatedProfit) }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Total Transaksi</p>
                        <p class="mt-1 text-lg font-bold text-slate-800">{{ totalSales }}</p>
                    </div>
                </div>

                <!-- Daily Totals -->
                <div class="card">
                    <div class="px-4 py-3 border-b border-slate-200">
                        <h3 class="font-medium text-slate-700">Total Harian</h3>
                    </div>
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-4 py-3 font-medium text-slate-700">Tanggal</th>
                                    <th class="px-4 py-3 font-medium text-slate-700 text-right">Total</th>
                                    <th class="px-4 py-3 font-medium text-slate-700 text-right">Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="day in dailyTotals" :key="day.date" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3">{{ day.date }}</td>
                                    <td class="px-4 py-3 text-right font-medium">{{ formatPrice(day.total) }}</td>
                                    <td class="px-4 py-3 text-right">{{ day.count }}</td>
                                </tr>
                                <tr v-if="dailyTotals.length === 0">
                                    <td colspan="3" class="py-6 text-center text-slate-500">Belum ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
