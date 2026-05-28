<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'

const props = defineProps({
    purchaseOrder: { type: Object, required: true },
    canApprove: { type: Boolean, default: false },
    canReceive: { type: Boolean, default: false },
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

const approveForm = useForm({})
const receiveForm = useForm({})
const cancelForm = useForm({})

const approve = () => {
    approveForm.post(route('purchase-orders.approve', props.purchaseOrder.id), {
        preserveScroll: true,
    })
}

const receive = () => {
    receiveForm.post(route('purchase-orders.receive', props.purchaseOrder.id), {
        preserveScroll: true,
    })
}

const cancel = () => {
    cancelForm.post(route('purchase-orders.cancel', props.purchaseOrder.id), {
        preserveScroll: true,
    })
}

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`
</script>

<template>
    <Head :title="`PO: ${purchaseOrder.po_number}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Purchase Order: {{ purchaseOrder.po_number }}</h2>
                <div class="flex gap-2">
                    <span class="rounded-full px-3 py-1 text-sm font-medium" :class="statusColors[purchaseOrder.status] || 'bg-slate-100 text-slate-700'">
                        {{ statusLabels[purchaseOrder.status] || purchaseOrder.status }}
                    </span>
                    <Link :href="route('purchase-orders.index')">
                        <SecondaryButton>Kembali</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <!-- Actions -->
                <div v-if="purchaseOrder.status === 'draft' || purchaseOrder.status === 'approved'" class="mb-6 flex gap-3">
                    <PrimaryButton v-if="purchaseOrder.status === 'draft' && canApprove" @click="approve" :disabled="approveForm.processing">Setujui</PrimaryButton>
                    <PrimaryButton v-if="purchaseOrder.status === 'approved' && canReceive" @click="receive" :disabled="receiveForm.processing">Terima Barang</PrimaryButton>
                    <DangerButton @click="cancel" :disabled="cancelForm.processing">Batalkan PO</DangerButton>
                </div>

                <!-- Info -->
                <div class="mb-6 card">
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-slate-500">PO Number</p>
                                <p class="font-mono font-medium">{{ purchaseOrder.po_number }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Tanggal</p>
                                <p>{{ purchaseOrder.created_at }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Supplier</p>
                                <p class="font-medium">{{ purchaseOrder.supplier?.name }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Dibuat Oleh</p>
                                <p>{{ purchaseOrder.creator?.name }}</p>
                            </div>
                            <div v-if="purchaseOrder.approver">
                                <p class="text-slate-500">Disetujui Oleh</p>
                                <p>{{ purchaseOrder.approver?.name }}</p>
                            </div>
                            <div v-if="purchaseOrder.received_at">
                                <p class="text-slate-500">Diterima Pada</p>
                                <p>{{ purchaseOrder.received_at }}</p>
                            </div>
                            <div v-if="purchaseOrder.notes" class="col-span-2">
                                <p class="text-slate-500">Catatan</p>
                                <p>{{ purchaseOrder.notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="card">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h3 class="font-medium text-slate-700">Item</h3>
                    </div>
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-6 py-3 font-medium text-slate-700">Produk</th>
                                    <th class="px-6 py-3 font-medium text-slate-700">SKU</th>
                                    <th class="px-6 py-3 font-medium text-slate-700 text-right">Qty</th>
                                    <th class="px-6 py-3 font-medium text-slate-700 text-right">Harga Beli</th>
                                    <th class="px-6 py-3 font-medium text-slate-700 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in purchaseOrder.items" :key="item.id" class="border-b border-slate-100">
                                    <td class="px-6 py-3 font-medium">{{ item.product?.name }}</td>
                                    <td class="px-6 py-3 font-mono text-xs">{{ item.product?.sku }}</td>
                                    <td class="px-6 py-3 text-right">{{ item.qty }}</td>
                                    <td class="px-6 py-3 text-right">{{ formatPrice(item.cost_price) }}</td>
                                    <td class="px-6 py-3 text-right font-medium">{{ formatPrice(item.subtotal) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="border-t border-slate-200 font-bold">
                                    <td colspan="4" class="px-6 py-3 text-right">Total</td>
                                    <td class="px-6 py-3 text-right">{{ formatPrice(purchaseOrder.total) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
