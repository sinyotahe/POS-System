<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
    stockOuts: { type: Object, required: true },
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
    <Head title="Barang Keluar" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Barang Keluar</h2>
                <Link :href="route('stock-outs.create')">
                    <PrimaryButton>Barang Keluar Baru</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-3 font-medium text-slate-700">Tanggal</th>
                                    <th class="py-3 font-medium text-slate-700">Tipe</th>
                                    <th class="py-3 font-medium text-slate-700">Jumlah Item</th>
                                    <th class="py-3 font-medium text-slate-700">Catatan</th>
                                    <th class="py-3 font-medium text-slate-700">Dibuat Oleh</th>
                                    <th class="py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="stockOut in stockOuts.data ?? []" :key="stockOut.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3">{{ stockOut.created_at }}</td>
                                    <td class="py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="typeColors[stockOut.type] || 'bg-slate-100 text-slate-700'">
                                            {{ typeLabels[stockOut.type] || stockOut.type }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ stockOut.items_count ?? stockOut.items?.length ?? 0 }}</td>
                                    <td class="py-3 max-w-xs truncate">{{ stockOut.notes || '-' }}</td>
                                    <td class="py-3">{{ stockOut.creator?.name }}</td>
                                    <td class="py-3">
                                        <Link :href="route('stock-outs.show', stockOut.id)">
                                            <SecondaryButton>Detail</SecondaryButton>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="(stockOuts.data ?? []).length === 0">
                                    <td colspan="6" class="py-6 text-center text-slate-500">Belum ada barang keluar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="stockOuts.links" class="flex justify-center gap-1 p-4">
                        <component :is="'a'" v-for="(link, i) in stockOuts.links" :key="i"
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
