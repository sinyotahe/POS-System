<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch } from 'vue'

const props = defineProps({
    sales: { type: Object, required: true },
    summary: { type: Object, default: () => ({ total_transactions: 0, total_revenue: 0 }) },
    productSales: { type: Array, default: () => [] },
    cashiers: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ period: 'daily', date_from: '', date_to: '', cashier_id: '' }) },
    userRole: { type: String, default: 'admin' },
})

const dateFrom = ref(props.filters.date_from ?? '')
const dateTo = ref(props.filters.date_to ?? '')
const period = ref(props.filters.period ?? 'daily')
const cashierFilter = ref(props.filters.cashier_id ?? '')

watch([dateFrom, dateTo, period, cashierFilter], () => {
    router.get(route('reports.sales'), {
        date_from: dateFrom.value,
        date_to: dateTo.value,
        period: period.value,
        cashier_id: cashierFilter.value,
    }, { preserveState: true, replace: true })
})

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`

const exportExcel = () => {
    const params = new URLSearchParams()
    if (dateFrom.value) params.set('date_from', dateFrom.value)
    if (dateTo.value) params.set('date_to', dateTo.value)
    if (period.value) params.set('period', period.value)
    if (cashierFilter.value) params.set('cashier_id', cashierFilter.value)
    window.open(route('reports.sales.export', 'xlsx') + '?' + params.toString(), '_blank')
}

const exportPdf = () => {
    const params = new URLSearchParams()
    if (dateFrom.value) params.set('date_from', dateFrom.value)
    if (dateTo.value) params.set('date_to', dateTo.value)
    if (period.value) params.set('period', period.value)
    if (cashierFilter.value) params.set('cashier_id', cashierFilter.value)
    window.open(route('reports.sales.export', 'pdf') + '?' + params.toString(), '_blank')
}
</script>

<template>
    <Head title="Laporan Penjualan" />

    <AuthenticatedLayout>
        <template #header>
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold leading-tight text-slate-800">Laporan Penjualan</h2>
                    <div v-if="userRole !== 'kasir'" class="flex gap-2">
                        <Link :href="route('reports.inventory')" class="text-sm text-primary-600 hover:text-primary-800">Inventory</Link>
                        <span class="text-slate-300">|</span>
                        <Link :href="route('reports.financial')" class="text-sm text-primary-600 hover:text-primary-800">Keuangan</Link>
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
                    <div>
                        <label class="block text-xs text-slate-600">Periode</label>
                        <select v-model="period" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <div v-if="userRole !== 'kasir'">
                        <label class="block text-xs text-slate-600">Kasir</label>
                        <select v-model="cashierFilter" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Kasir</option>
                            <option v-for="c in cashiers" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div v-if="userRole !== 'kasir'" class="flex gap-2">
                        <button @click="exportExcel" class="rounded-md bg-green-600 px-3 py-2 text-xs font-medium text-white hover:bg-green-500">Export Excel</button>
                        <button @click="exportPdf" class="rounded-md bg-red-600 px-3 py-2 text-xs font-medium text-white hover:bg-red-500">Export PDF</button>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mb-4 grid grid-cols-3 gap-4">
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-slate-800">{{ formatPrice(summary.total_revenue) }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Total Transaksi</p>
                        <p class="text-2xl font-bold text-slate-800">{{ summary.total_transactions }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Rata-rata Transaksi</p>
                        <p class="text-2xl font-bold text-slate-800">{{ summary.total_transactions > 0 ? formatPrice(summary.total_revenue / summary.total_transactions) : 'Rp 0' }}</p>
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-4 py-3 font-medium text-slate-700">Invoice</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Tanggal</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Kasir</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Grand Total</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sale in sales.data" :key="sale.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3 font-mono text-xs">{{ sale.invoice_number }}</td>
                                    <td class="px-4 py-3">{{ sale.created_at }}</td>
                                    <td class="px-4 py-3">{{ sale.cashier?.name }}</td>
                                    <td class="px-4 py-3 font-medium">{{ formatPrice(sale.grand_total) }}</td>
                                    <td class="px-4 py-3">{{ sale.payment_method }}</td>
                                </tr>
                                <tr v-if="sales.data.length === 0">
                                    <td colspan="5" class="py-6 text-center text-slate-500">Belum ada data penjualan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="sales.links" class="mt-4 flex justify-center gap-1">
                    <component :is="'a'" v-for="(link, i) in sales.links" :key="i"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="rounded-md px-3 py-1 text-sm"
                        :class="link.active ? 'bg-primary-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100'"
                    />
                </div>

                <!-- Top Products -->
                <div v-if="productSales.length" class="mt-6 card">
                    <div class="px-4 py-3 border-b border-slate-200">
                        <h3 class="font-medium text-slate-700">Produk Terlaris</h3>
                    </div>
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-4 py-2 font-medium text-slate-700">Produk</th>
                                    <th class="px-4 py-2 font-medium text-slate-700 text-right">Qty</th>
                                    <th class="px-4 py-2 font-medium text-slate-700 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in productSales" :key="item.product_id" class="border-b border-slate-100">
                                    <td class="px-4 py-2">{{ item.product?.name }}</td>
                                    <td class="px-4 py-2 text-right">{{ item.total_qty }}</td>
                                    <td class="px-4 py-2 text-right">{{ formatPrice(item.total_subtotal) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
