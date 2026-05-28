<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch } from 'vue'

const props = defineProps({
    products: { type: Object, required: true },
    recentMovements: { type: Array, default: () => [] },
    summary: { type: Object, default: () => ({ total_products: 0, active_products: 0, low_stock: 0, out_of_stock: 0, total_stock_value: 0 }) },
    filters: { type: Object, default: () => ({ search: '', stock_status: '' }) },
})

const search = ref(props.filters.search ?? '')
const stockStatus = ref(props.filters.stock_status ?? '')

watch([search, stockStatus], () => {
    router.get(route('reports.inventory'), {
        search: search.value,
        stock_status: stockStatus.value,
    }, { preserveState: true, replace: true })
})

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`

const exportExcel = () => {
    const params = new URLSearchParams()
    if (search.value) params.set('search', search.value)
    if (stockStatus.value) params.set('stock_status', stockStatus.value)
    window.open(route('reports.inventory.export', 'xlsx') + '?' + params.toString(), '_blank')
}

const exportPdf = () => {
    const params = new URLSearchParams()
    if (search.value) params.set('search', search.value)
    if (stockStatus.value) params.set('stock_status', stockStatus.value)
    window.open(route('reports.inventory.export', 'pdf') + '?' + params.toString(), '_blank')
}
</script>

<template>
    <Head title="Laporan Inventory" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Laporan Inventory</h2>
                <div class="flex gap-2">
                    <Link :href="route('reports.sales')" class="text-sm text-primary-600 hover:text-primary-800">Penjualan</Link>
                    <span class="text-slate-300">|</span>
                    <Link :href="route('reports.financial')" class="text-sm text-primary-600 hover:text-primary-800">Keuangan</Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Summary -->
                <div class="mb-4 grid grid-cols-5 gap-4">
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Total Produk</p>
                        <p class="text-xl font-bold text-slate-800">{{ summary.total_products }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Aktif</p>
                        <p class="text-xl font-bold text-green-600">{{ summary.active_products }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Stok Minimum</p>
                        <p class="text-xl font-bold text-orange-600">{{ summary.low_stock }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Stok Habis</p>
                        <p class="text-xl font-bold text-red-600">{{ summary.out_of_stock }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-200">
                        <p class="text-xs text-slate-500">Nilai Stok</p>
                        <p class="text-xl font-bold text-slate-800">{{ formatPrice(summary.total_stock_value) }}</p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card p-4 mb-6 flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-xs text-slate-600">Cari</label>
                        <TextInput v-model="search" placeholder="Nama atau SKU..." class="mt-1 w-56" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Status Stok</label>
                        <select v-model="stockStatus" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua</option>
                            <option value="low">Minimum</option>
                            <option value="out">Habis</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button @click="exportExcel" class="rounded-md bg-green-600 px-3 py-2 text-xs font-medium text-white hover:bg-green-500">Export Excel</button>
                        <button @click="exportPdf" class="rounded-md bg-red-600 px-3 py-2 text-xs font-medium text-white hover:bg-red-500">Export PDF</button>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-4 py-3 font-medium text-slate-700">SKU</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Nama</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Kategori</th>
                                    <th class="px-4 py-3 font-medium text-slate-700 text-right">Stok</th>
                                    <th class="px-4 py-3 font-medium text-slate-700 text-right">Minimum</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data" :key="product.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3 font-mono text-xs">{{ product.sku }}</td>
                                    <td class="px-4 py-3 font-medium">{{ product.name }}</td>
                                    <td class="px-4 py-3">{{ product.category?.name }}</td>
                                    <td class="px-4 py-3 text-right" :class="product.stock <= product.minimum_stock ? 'font-semibold text-red-600' : ''">{{ product.stock }}</td>
                                    <td class="px-4 py-3 text-right">{{ product.minimum_stock }}</td>
                                    <td class="px-4 py-3">
                                        <span v-if="product.stock <= product.minimum_stock" class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Minimum</span>
                                        <span v-else class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aman</span>
                                    </td>
                                </tr>
                                <tr v-if="products.data.length === 0">
                                    <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada produk</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="products.links" class="mt-4 flex justify-center gap-1">
                    <component :is="'a'" v-for="(link, i) in products.links" :key="i"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="rounded-md px-3 py-1 text-sm"
                        :class="link.active ? 'bg-primary-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100'"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
