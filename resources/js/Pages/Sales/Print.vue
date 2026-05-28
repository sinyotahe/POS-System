<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const props = defineProps({
    sale: { type: Object, required: true },
})

const paymentLabels = {
    cash: 'Tunai',
    transfer: 'Transfer',
    qris: 'QRIS',
    'e-wallet': 'E-Wallet',
}

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`

onMounted(() => {
    window.print()
})
</script>

<template>
    <Head title="Cetak Struk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Cetak Struk</h2>
                <div class="flex gap-2 no-print">
                    <Link :href="route('sales.show', sale.id)">
                        <span class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">← Kembali ke Detail</span>
                    </Link>
                    <Link :href="route('pos.index')">
                        <span class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700">← Kembali ke POS</span>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-8">
                        <div class="mb-6 text-center border-b border-slate-200 pb-4">
                            <h3 class="text-lg font-bold">POS System</h3>
                            <p class="text-sm text-slate-500">Struk Penjualan</p>
                        </div>

                        <div class="mb-4 space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Invoice</span>
                                <span class="font-mono font-medium">{{ sale.invoice_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tanggal</span>
                                <span>{{ sale.created_at }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Kasir</span>
                                <span>{{ sale.cashier?.name }}</span>
                            </div>
                            <div v-if="sale.customer_name" class="flex justify-between">
                                <span class="text-slate-500">Pelanggan</span>
                                <span>{{ sale.customer_name }}</span>
                            </div>
                        </div>

                        <div class="mb-4 table-wrapper overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-t border-b border-slate-200">
                                        <th class="py-2 text-left font-medium text-slate-600">Item</th>
                                        <th class="py-2 text-center font-medium text-slate-600">Qty</th>
                                        <th class="py-2 text-right font-medium text-slate-600">Harga</th>
                                        <th class="py-2 text-right font-medium text-slate-600">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in sale.items" :key="item.id" class="border-b border-slate-100">
                                        <td class="py-2">{{ item.product?.name }}</td>
                                        <td class="py-2 text-center">{{ item.qty }}</td>
                                        <td class="py-2 text-right">{{ formatPrice(item.price) }}</td>
                                        <td class="py-2 text-right font-medium">{{ formatPrice(item.subtotal) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="border-t border-slate-200 pt-3 text-sm">
                            <div class="flex justify-between py-1">
                                <span class="text-slate-500">Subtotal</span>
                                <span>{{ formatPrice(sale.total) }}</span>
                            </div>
                            <div v-if="sale.discount > 0" class="flex justify-between py-1">
                                <span class="text-slate-500">Diskon</span>
                                <span class="text-red-600">-{{ formatPrice(sale.discount) }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-slate-500">Pajak ({{ sale.tax_rate ? Math.round(sale.tax_rate * 100) : 0 }}%)</span>
                                <span>{{ formatPrice(sale.tax) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-200 py-2 text-base font-bold">
                                <span>Grand Total</span>
                                <span>{{ formatPrice(sale.grand_total) }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-slate-500">Pembayaran</span>
                                <span>{{ paymentLabels[sale.payment_method] || sale.payment_method }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-slate-500">Dibayar</span>
                                <span>{{ formatPrice(sale.paid_amount) }}</span>
                            </div>
                            <div class="flex justify-between py-1 text-green-600 font-medium">
                                <span>Kembali</span>
                                <span>{{ formatPrice(sale.change_amount) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 text-center text-xs text-slate-400 border-t border-slate-200 pt-4">
                            Terima kasih atas kunjungan Anda
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
@media print {
    .sm\:rounded-lg { box-shadow: none !important; }
    header { display: none !important; }
    nav { display: none !important; }
    .py-12 { padding-top: 0 !important; }
}
</style>
