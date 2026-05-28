<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    movements: { type: Object, required: true },
    products: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ product_id: '', type: '', date_from: '', date_to: '' }) },
})

const productFilter = ref(props.filters.product_id ?? '')
const typeFilter = ref(props.filters.type ?? '')
const dateFrom = ref(props.filters.date_from ?? '')
const dateTo = ref(props.filters.date_to ?? '')

watch([productFilter, typeFilter, dateFrom, dateTo], () => {
    router.get(route('stock-movements.index'), {
        product_id: productFilter.value,
        type: typeFilter.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
    }, { preserveState: true, replace: true })
})

const typeLabels = {
    in: 'Masuk',
    out: 'Keluar',
    sale: 'Penjualan',
    void: 'Void',
    adjustment: 'Penyesuaian',
}

const typeColors = {
    in: 'bg-green-100 text-green-700',
    out: 'bg-red-100 text-red-700',
    sale: 'bg-blue-100 text-blue-700',
    void: 'bg-slate-100 text-slate-700',
    adjustment: 'bg-yellow-100 text-yellow-700',
}
</script>

<template>
    <Head title="Mutasi Stok" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Mutasi Stok</h2>
                <Link v-if="usePage().props.auth.user.role === 'admin'" :href="route('stock-adjustments.create')" class="text-sm font-medium text-primary-600 hover:text-primary-800">
                    + Penyesuaian Stok
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card p-4 mb-6 flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-xs text-slate-600">Produk</label>
                        <select v-model="productFilter" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Produk</option>
                            <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Tipe</label>
                        <select v-model="typeFilter" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Tipe</option>
                            <option value="in">Masuk</option>
                            <option value="out">Keluar</option>
                            <option value="sale">Penjualan</option>
                            <option value="void">Void</option>
                            <option value="adjustment">Penyesuaian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Dari</label>
                        <TextInput v-model="dateFrom" type="date" class="mt-1 w-44" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Sampai</label>
                        <TextInput v-model="dateTo" type="date" class="mt-1 w-44" />
                    </div>
                </div>

                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-3 font-medium text-slate-700">Tanggal</th>
                                    <th class="py-3 font-medium text-slate-700">Produk</th>
                                    <th class="py-3 font-medium text-slate-700">Tipe</th>
                                    <th class="py-3 font-medium text-slate-700">Qty</th>
                                    <th class="py-3 font-medium text-slate-700">Stok Sebelum</th>
                                    <th class="py-3 font-medium text-slate-700">Stok Sesudah</th>
                                    <th class="py-3 font-medium text-slate-700">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="movement in movements.data ?? []" :key="movement.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3">{{ movement.created_at }}</td>
                                    <td class="py-3 font-medium">{{ movement.product?.name }}</td>
                                    <td class="py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="typeColors[movement.type] || 'bg-slate-100 text-slate-700'">
                                            {{ typeLabels[movement.type] || movement.type }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-mono font-medium">{{ movement.quantity > 0 ? '+' : '' }}{{ movement.quantity }}</td>
                                    <td class="py-3">{{ movement.before_stock }}</td>
                                    <td class="py-3">{{ movement.after_stock }}</td>
                                    <td class="py-3 text-slate-500">{{ movement.user?.name }}</td>
                                </tr>
                                <tr v-if="(movements.data ?? []).length === 0">
                                    <td colspan="7" class="py-6 text-center text-slate-500">Belum ada mutasi stok</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="movements.links" class="flex justify-center gap-1 p-4">
                        <component :is="'a'" v-for="(link, i) in movements.links" :key="i"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="rounded-md px-3 py-1 text-sm"
                            :class="link.active ? 'bg-primary-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100'"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
