<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import StatCard from '@/Components/StatCard.vue'
import Badge from '@/Components/Badge.vue'

const props = defineProps({
    today_sales_total: { type: Number, default: 0 },
    today_transactions_count: { type: Number, default: 0 },
    low_stock_count: { type: Number, default: 0 },
    top_products: { type: Array, default: () => [] },
    top_products_month: { type: Array, default: () => [] },
    low_stock_products: { type: Array, default: () => [] },
    sales_chart: { type: Array, default: () => [] },
    transactions_chart: { type: Array, default: () => [] },
    this_month_omzet: { type: Number, default: 0 },
    this_month_transactions: { type: Number, default: 0 },
    last_month_omzet: { type: Number, default: 0 },
    growth_percent: { type: Number, default: 0 },
    month_gross_profit: { type: Number, default: 0 },
})

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`

const maxChartValue = computed(() => Math.max(...props.sales_chart.map((d) => d.total), 1))
const maxTransactionsValue = computed(() => Math.max(...props.transactions_chart.map((d) => d.count), 1))

const growthText = computed(() => {
    const g = props.growth_percent
    return g > 0 ? `+${g}%` : g < 0 ? `${g}%` : `0%`
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="page-header">
            <h2 class="text-2xl font-bold text-slate-900">Dashboard</h2>
            <p class="mt-1 text-sm text-slate-500">Ringkasan bisnis Anda hari ini</p>
        </div>

        <!-- Stat Cards -->
        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <StatCard
                title="Penjualan Hari Ini"
                :value="formatPrice(today_sales_total)"
                icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                color-class="bg-emerald-50 text-emerald-600"
                :trend="`${today_transactions_count} transaksi`"
            />
            <StatCard
                title="Omzet Bulan Ini"
                :value="formatPrice(this_month_omzet)"
                icon="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                color-class="bg-blue-50 text-blue-600"
                :trend="growthText"
                trendLabel="dari bulan lalu"
                v-if="$page.props.auth.user.role !== 'kasir'"
            />
            <StatCard
                title="Laba Kotor"
                :value="formatPrice(month_gross_profit)"
                icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                color-class="bg-violet-50 text-violet-600"
                :trend="`${this_month_transactions} transaksi`"
                v-if="$page.props.auth.user.role !== 'kasir'"
            />
            <StatCard
                title="Stok Minimum"
                :value="String(low_stock_count)"
                icon="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                :color-class="low_stock_count > 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600'"
            />
        </div>

        <!-- Quick Actions -->
        <div class="mb-6">
            <h3 class="mb-3 text-sm font-semibold text-slate-700">⚡ Aksi Cepat</h3>
            <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 lg:grid-cols-6">
                <Link v-if="$page.props.auth.user.role !== 'kasir'" :href="route('products.create')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Produk Baru</span>
                </Link>

                <Link v-if="$page.props.auth.user.role !== 'kasir'" :href="route('products.index')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Daftar Produk</span>
                </Link>

                <Link v-if="$page.props.auth.user.role === 'admin'" :href="route('stock-ins.create')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Stok Masuk</span>
                </Link>

                <Link v-if="$page.props.auth.user.role === 'admin'" :href="route('stock-outs.create')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Stok Keluar</span>
                </Link>

                <Link v-if="$page.props.auth.user.role === 'admin'" :href="route('stock-transfers.index')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-50 text-cyan-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Transfer Stok</span>
                </Link>

                <Link v-if="$page.props.auth.user.role === 'admin'" :href="route('stock-movements.index')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-50 text-teal-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Mutasi Stok</span>
                </Link>

                <Link v-if="$page.props.auth.user.role !== 'owner'" :href="route('pos.index')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">POS</span>
                </Link>

                <Link :href="route('sales.index')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Penjualan</span>
                </Link>

                <Link v-if="$page.props.auth.user.role === 'kasir'" :href="route('reports.index')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-orange-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Laporan Penjualan</span>
                </Link>

                <Link v-if="$page.props.auth.user.role === 'admin'" :href="route('products.barcode-print')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Cetak Barcode</span>
                </Link>

                <Link v-if="$page.props.auth.user.role !== 'kasir'" :href="route('stock-transfers.index')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-orange-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Laporan</span>
                </Link>

                <Link v-if="$page.props.auth.user.role !== 'kasir'" :href="route('reports.branch-comparison')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Banding Cabang</span>
                </Link>

                <Link v-if="$page.props.auth.user.role !== 'kasir'" :href="route('purchase-orders.index')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-50 text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Pembelian PO</span>
                </Link>

                <Link v-if="$page.props.auth.user.role === 'admin'" :href="route('settings.edit')"
                    class="flex flex-col items-center gap-2 rounded-xl border bg-white p-4 shadow-sm transition-all hover:border-primary-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-50 text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600 text-center">Pengaturan</span>
                </Link>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="card p-5">
                <h3 class="mb-4 text-sm font-semibold text-slate-700">Grafik Penjualan (7 Hari)</h3>
                <div v-if="sales_chart.length" class="flex items-end gap-2" style="height: 160px">
                    <div v-for="(day, i) in sales_chart" :key="i" class="flex flex-1 flex-col items-center">
                        <div
                            class="w-full rounded-t bg-gradient-to-t from-blue-500 to-blue-400 transition-all hover:from-blue-600"
                            :style="{ height: `${(day.total / maxChartValue) * 140}px` }"
                            :title="`${day.date}: ${formatPrice(day.total)}`"
                        ></div>
                        <p class="mt-1 text-[10px] text-slate-500 truncate w-full text-center">{{ day.label }}</p>
                    </div>
                </div>
                <p v-else class="py-8 text-center text-sm text-slate-400">Belum ada data penjualan</p>
            </div>

            <div class="card p-5">
                <h3 class="mb-4 text-sm font-semibold text-slate-700">Grafik Transaksi (7 Hari)</h3>
                <div v-if="transactions_chart.length" class="flex items-end gap-2" style="height: 160px">
                    <div v-for="(day, i) in transactions_chart" :key="i" class="flex flex-1 flex-col items-center">
                        <div
                            class="w-full rounded-t bg-gradient-to-t from-emerald-500 to-emerald-400 transition-all hover:from-emerald-600"
                            :style="{ height: `${(day.count / maxTransactionsValue) * 140}px` }"
                            :title="`${day.date}: ${day.count} transaksi`"
                        ></div>
                        <p class="mt-1 text-[10px] text-slate-500 truncate w-full text-center">{{ day.label }}</p>
                    </div>
                </div>
                <p v-else class="py-8 text-center text-sm text-slate-400">Belum ada data transaksi</p>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <!-- Top Products Today -->
            <div class="card p-5">
                <h3 class="mb-4 text-sm font-semibold text-slate-700">Produk Terlaris Hari Ini</h3>
                <div v-if="top_products.length" class="space-y-2">
                    <div v-for="(product, i) in top_products" :key="i" class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2">
                        <div class="flex items-center gap-3">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold" :class="i === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-600'">{{ i + 1 }}</span>
                            <span class="text-sm font-medium text-slate-700">{{ product.product?.name || product.name }}</span>
                        </div>
                        <Badge :type="i === 0 ? 'yellow' : 'gray'">{{ product.total_qty }} terjual</Badge>
                    </div>
                </div>
                <p v-else class="py-8 text-center text-sm text-slate-400">Belum ada penjualan hari ini</p>
            </div>

            <!-- Top Products Month -->
            <div class="card p-5">
                <h3 class="mb-4 text-sm font-semibold text-slate-700">Produk Terlaris Bulan Ini</h3>
                <div v-if="top_products_month.length" class="space-y-2">
                    <div v-for="(product, i) in top_products_month" :key="i" class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2">
                        <div class="flex items-center gap-3">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold" :class="i === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-600'">{{ i + 1 }}</span>
                            <span class="text-sm font-medium text-slate-700">{{ product.product?.name || product.name }}</span>
                        </div>
                        <Badge :type="i === 0 ? 'yellow' : 'gray'">{{ product.total_qty }} terjual</Badge>
                    </div>
                </div>
                <p v-else class="py-8 text-center text-sm text-slate-400">Belum ada data bulan ini</p>
            </div>

            <!-- Low Stock -->
            <div class="card p-5">
                <h3 class="mb-4 text-sm font-semibold text-slate-700">Stok Minimum</h3>
                <div v-if="low_stock_products.length" class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="py-2 font-medium text-slate-500">Produk</th>
                                <th class="py-2 font-medium text-slate-500">Stok</th>
                                <th class="py-2 font-medium text-slate-500">Min</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in low_stock_products" :key="product.id" class="border-b border-slate-100">
                                <td class="py-2 font-medium text-slate-700">{{ product.name }}</td>
                                <td class="py-2 font-semibold text-red-600">{{ product.stock }}</td>
                                <td class="py-2 text-slate-500">{{ product.minimum_stock }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p v-else class="py-4 text-center text-sm text-slate-400">Semua stok dalam kondisi aman</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
