<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch, computed } from 'vue'

const props = defineProps({
    salesSummary: { type: Object, default: () => ({ total: 0, count: 0, data: [] }) },
    products: { type: Array, default: () => [] },
    lowStockProducts: { type: Array, default: () => [] },
    financialSummary: { type: Object, default: () => ({ omzet: 0, gross_profit: 0, profit_margin: 0 }) },
    filters: { type: Object, default: () => ({ date_from: '', date_to: '', period: 'daily' }) },
    userRole: { type: String, default: 'admin' },
})

const activeTab = ref('sales')
const dateFrom = ref(props.filters.date_from ?? '')
const dateTo = ref(props.filters.date_to ?? '')
const period = ref(props.filters.period ?? 'daily')
const productFilter = ref('')

watch([dateFrom, dateTo, period], () => {
    router.get(route('reports.index'), {
        tab: activeTab.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
        period: period.value,
    }, { preserveState: true, replace: true })
})

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`

const allTabs = [
    { key: 'sales', label: 'Penjualan' },
    { key: 'inventory', label: 'Inventory' },
    { key: 'financial', label: 'Keuangan' },
]

const tabs = computed(() => {
    if (props.userRole === 'kasir') {
        return allTabs.filter((t) => t.key === 'sales')
    }
    return allTabs
})

const filteredProducts = computed(() => {
    if (!productFilter.value) return props.products
    const q = productFilter.value.toLowerCase()
    return props.products.filter((p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q))
})
</script>

<template>
    <Head title="Laporan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Laporan</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Tabs -->
                <div class="mb-6 flex gap-1 rounded-lg bg-slate-100 p-1">
                    <button
                        v-for="tab in tabs"
                        :key="tab.key"
                        @click="activeTab = tab.key"
                        class="flex-1 rounded-md px-4 py-2 text-sm font-medium transition"
                        :class="activeTab === tab.key ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                    >
                        {{ tab.label }}
                    </button>
                </div>

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
                    <div v-if="activeTab === 'sales'">
                        <label class="block text-xs text-slate-600">Periode</label>
                        <select v-model="period" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <div v-if="activeTab === 'inventory'">
                        <label class="block text-xs text-slate-600">Cari Produk</label>
                        <TextInput v-model="productFilter" placeholder="Nama atau SKU..." class="mt-1 w-56" />
                    </div>
                </div>

                <!-- Sales Tab -->
                <div v-if="activeTab === 'sales'">
                    <div class="mb-4 grid grid-cols-3 gap-4">
                        <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                            <p class="text-xs text-slate-500">Total Penjualan</p>
                            <p class="text-2xl font-bold text-slate-800">{{ formatPrice(salesSummary.total) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                            <p class="text-xs text-slate-500">Jumlah Transaksi</p>
                            <p class="text-2xl font-bold text-slate-800">{{ salesSummary.count }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                            <p class="text-xs text-slate-500">Rata-rata Transaksi</p>
                            <p class="text-2xl font-bold text-slate-800">{{ salesSummary.count > 0 ? formatPrice(salesSummary.total / salesSummary.count) : 'Rp 0' }}</p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="table-wrapper overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="py-3 font-medium text-slate-700">Periode</th>
                                        <th class="py-3 font-medium text-slate-700">Total</th>
                                        <th class="py-3 font-medium text-slate-700">Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, i) in salesSummary.data" :key="i" class="border-b border-slate-100 hover:bg-slate-50">
                                        <td class="py-3">{{ row.period }}</td>
                                        <td class="py-3 font-medium">{{ formatPrice(row.total) }}</td>
                                        <td class="py-3">{{ row.count }}</td>
                                    </tr>
                                    <tr v-if="salesSummary.data.length === 0">
                                        <td colspan="3" class="py-6 text-center text-slate-500">Belum ada data penjualan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Inventory Tab -->
                <div v-if="activeTab === 'inventory'">
                    <div v-if="lowStockProducts.length" class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
                        <h4 class="flex items-center gap-2 font-semibold text-red-700">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            Peringatan Stok Minimum
                        </h4>
                        <p class="mt-1 text-sm text-red-600">{{ lowStockProducts.length }} produk memiliki stok di bawah minimum.</p>
                    </div>

                    <div class="card">
                        <div class="table-wrapper overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="py-3 font-medium text-slate-700">SKU</th>
                                        <th class="py-3 font-medium text-slate-700">Nama</th>
                                        <th class="py-3 font-medium text-slate-700">Kategori</th>
                                        <th class="py-3 font-medium text-slate-700">Stok</th>
                                        <th class="py-3 font-medium text-slate-700">Minimum</th>
                                        <th class="py-3 font-medium text-slate-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in filteredProducts" :key="product.id" class="border-b border-slate-100 hover:bg-slate-50">
                                        <td class="py-3 font-mono text-xs">{{ product.sku }}</td>
                                        <td class="py-3 font-medium">{{ product.name }}</td>
                                        <td class="py-3">{{ product.category?.name }}</td>
                                        <td class="py-3" :class="product.stock <= product.minimum_stock ? 'font-semibold text-red-600' : ''">{{ product.stock }}</td>
                                        <td class="py-3">{{ product.minimum_stock }}</td>
                                        <td class="py-3">
                                            <span v-if="product.stock <= product.minimum_stock" class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Minimum</span>
                                            <span v-else class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aman</span>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredProducts.length === 0">
                                        <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada produk</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Financial Tab -->
                <div v-if="activeTab === 'financial'">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="rounded-lg bg-white p-6 shadow-sm border border-slate-200">
                            <p class="text-xs text-slate-500">Omzet</p>
                            <p class="mt-1 text-3xl font-bold text-slate-800">{{ formatPrice(financialSummary.omzet) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-6 shadow-sm border border-slate-200">
                            <p class="text-xs text-slate-500">Laba Kotor</p>
                            <p class="mt-1 text-3xl font-bold text-green-600">{{ formatPrice(financialSummary.gross_profit) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-6 shadow-sm border border-slate-200">
                            <p class="text-xs text-slate-500">Margin Keuntungan</p>
                            <p class="mt-1 text-3xl font-bold text-primary-600">{{ financialSummary.profit_margin }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
