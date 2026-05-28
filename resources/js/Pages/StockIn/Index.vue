<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    stockIns: { type: Object, required: true },
    suppliers: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ date_from: '', date_to: '', supplier_id: '' }) },
})

const dateFrom = ref(props.filters.date_from ?? '')
const dateTo = ref(props.filters.date_to ?? '')
const supplierFilter = ref(props.filters.supplier_id ?? '')

watch([dateFrom, dateTo, supplierFilter], () => {
    router.get(route('stock-ins.index'), {
        date_from: dateFrom.value,
        date_to: dateTo.value,
        supplier_id: supplierFilter.value,
    }, { preserveState: true, replace: true })
})
</script>

<template>
    <Head title="Barang Masuk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Barang Masuk</h2>
                <Link :href="route('stock-ins.create')">
                    <PrimaryButton>Barang Masuk Baru</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card p-4 mb-6 flex flex-wrap items-center gap-4">
                    <div>
                        <label class="block text-xs text-slate-600">Dari</label>
                        <TextInput v-model="dateFrom" type="date" class="w-44" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Sampai</label>
                        <TextInput v-model="dateTo" type="date" class="w-44" />
                    </div>
                    <select v-model="supplierFilter" class="mt-5 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Semua Supplier</option>
                        <option v-for="s in suppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                    </select>
                </div>

                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-3 font-medium text-slate-700">Tanggal</th>
                                    <th class="py-3 font-medium text-slate-700">Invoice</th>
                                    <th class="py-3 font-medium text-slate-700">Supplier</th>
                                    <th class="py-3 font-medium text-slate-700">Jumlah Item</th>
                                    <th class="py-3 font-medium text-slate-700">Dibuat Oleh</th>
                                    <th class="py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="stockIn in stockIns.data ?? []" :key="stockIn.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3">{{ stockIn.created_at }}</td>
                                    <td class="py-3 font-mono text-xs">{{ stockIn.invoice_number }}</td>
                                    <td class="py-3">{{ stockIn.supplier?.name }}</td>
                                    <td class="py-3">{{ stockIn.items_count ?? stockIn.items?.length ?? 0 }}</td>
                                    <td class="py-3">{{ stockIn.creator?.name }}</td>
                                    <td class="py-3">
                                        <Link :href="route('stock-ins.show', stockIn.id)">
                                            <SecondaryButton>Detail</SecondaryButton>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="(stockIns.data ?? []).length === 0">
                                    <td colspan="6" class="py-6 text-center text-slate-500">Belum ada barang masuk</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="stockIns.links" class="flex justify-center gap-1 p-4">
                        <component :is="'a'" v-for="(link, i) in stockIns.links" :key="i"
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
