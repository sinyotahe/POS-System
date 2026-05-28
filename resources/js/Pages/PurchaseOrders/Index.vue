<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    purchaseOrders: { type: Object, required: true },
    suppliers: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ status: '', supplier_id: '', date_from: '', date_to: '' }) },
})

const statusFilter = ref(props.filters.status ?? '')
const supplierFilter = ref(props.filters.supplier_id ?? '')
const dateFrom = ref(props.filters.date_from ?? '')
const dateTo = ref(props.filters.date_to ?? '')

watch([statusFilter, supplierFilter, dateFrom, dateTo], () => {
    router.get(route('purchase-orders.index'), {
        status: statusFilter.value,
        supplier_id: supplierFilter.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
    }, { preserveState: true, replace: true })
})

const statusLabels = {
    draft: 'Draft',
    approved: 'Disetujui',
    received: 'Diterima',
    cancelled: 'Dibatalkan',
}

const statusColors = {
    draft: 'bg-slate-100 text-slate-700',
    approved: 'bg-blue-100 text-blue-700',
    received: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
}

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`
</script>

<template>
    <Head title="Purchase Order" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Purchase Order</h2>
                <Link :href="route('purchase-orders.create')">
                    <PrimaryButton>Buat PO</PrimaryButton>
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
                    <div>
                        <label class="block text-xs text-slate-600">Status</label>
                        <select v-model="statusFilter" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua</option>
                            <option value="draft">Draft</option>
                            <option value="approved">Disetujui</option>
                            <option value="received">Diterima</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Supplier</label>
                        <select v-model="supplierFilter" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Supplier</option>
                            <option v-for="s in suppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-4 py-3 font-medium text-slate-700">PO Number</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Tanggal</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Supplier</th>
                                    <th class="px-4 py-3 font-medium text-slate-700 text-right">Total</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Status</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Dibuat Oleh</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="po in purchaseOrders.data ?? []" :key="po.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3 font-mono text-xs font-medium">{{ po.po_number }}</td>
                                    <td class="px-4 py-3">{{ po.created_at }}</td>
                                    <td class="px-4 py-3">{{ po.supplier?.name }}</td>
                                    <td class="px-4 py-3 text-right font-medium">{{ formatPrice(po.total) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="statusColors[po.status] || 'bg-slate-100 text-slate-700'">
                                            {{ statusLabels[po.status] || po.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ po.creator?.name }}</td>
                                    <td class="px-4 py-3">
                                        <Link :href="route('purchase-orders.show', po.id)">
                                            <SecondaryButton>Detail</SecondaryButton>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="(purchaseOrders.data ?? []).length === 0">
                                    <td colspan="7" class="py-6 text-center text-slate-500">Belum ada Purchase Order</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="purchaseOrders.links" class="mt-4 flex justify-center gap-1">
                    <component :is="'a'" v-for="(link, i) in purchaseOrders.links" :key="i"
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
