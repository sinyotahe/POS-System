<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
    stockOut: { type: Object, required: true },
})

const typeLabels = {
    rusak: 'Rusak',
    hilang: 'Hilang',
    retur_supplier: 'Retur Supplier',
    pemakaian_internal: 'Pemakaian Internal',
}

const typeColors = {
    rusak: 'bg-red-100 text-red-700',
    hilang: 'bg-yellow-100 text-yellow-700',
    retur_supplier: 'bg-blue-100 text-blue-700',
    pemakaian_internal: 'bg-purple-100 text-purple-700',
}
</script>

<template>
    <Head title="Detail Barang Keluar" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Detail Barang Keluar</h2>
                <Link :href="route('stock-outs.index')">
                    <SecondaryButton>Kembali</SecondaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <div class="mb-6 grid grid-cols-3 gap-4">
                            <div>
                                <span class="text-xs text-slate-500">Tipe</span>
                                <p class="font-medium">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="typeColors[stockOut.type]">
                                        {{ typeLabels[stockOut.type] || stockOut.type }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500">Tanggal</span>
                                <p class="font-medium">{{ stockOut.created_at }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500">Dibuat Oleh</span>
                                <p class="font-medium">{{ stockOut.creator?.name }}</p>
                            </div>
                            <div class="col-span-3">
                                <span class="text-xs text-slate-500">Catatan</span>
                                <p class="font-medium">{{ stockOut.notes || '-' }}</p>
                            </div>
                        </div>

                        <div class="table-wrapper overflow-x-auto rounded-md border border-slate-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-slate-600">Produk</th>
                                        <th class="px-4 py-2 font-medium text-slate-600">SKU</th>
                                        <th class="px-4 py-2 font-medium text-slate-600">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in stockOut.items" :key="item.id" class="border-t border-slate-100">
                                        <td class="px-4 py-2">{{ item.product?.name }}</td>
                                        <td class="px-4 py-2 text-xs text-slate-500">{{ item.product?.sku }}</td>
                                        <td class="px-4 py-2">{{ item.qty }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-slate-50">
                                    <tr>
                                        <td colspan="2" class="px-4 py-2 text-right font-medium text-slate-600">Total Item</td>
                                        <td class="px-4 py-2 font-bold">{{ stockOut.items.reduce((s, i) => s + i.qty, 0) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
