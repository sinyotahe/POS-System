<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'

const props = defineProps({
    sale: { type: Object, required: true },
})

const user = usePage().props.auth.user

const voidSale = () => {
    if (!confirm('Yakin ingin void transaksi ini? Stok akan dikembalikan.')) return
    const reason = prompt('Alasan void (opsional):')
    router.post(route('sales.void', props.sale.id), { reason }, {
        preserveScroll: true,
    })
}

const paymentLabels = {
    cash: 'Tunai',
    transfer: 'Transfer',
    qris: 'QRIS',
    'e-wallet': 'E-Wallet',
}

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`

const printReceipt = () => {
    window.print()
}

const printThermal = () => {
    window.open(route('sales.print-thermal', props.sale.id), '_blank')
}
</script>

<template>
    <Head title="Detail Penjualan" />

    <AuthenticatedLayout>
        <template #header>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-semibold leading-tight text-slate-800">Detail Penjualan</h2>
                        <span v-if="sale.voided_at" class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">VOID</span>
                    </div>
                    <div class="flex gap-2 no-print">
                        <PrimaryButton @click="printReceipt">Cetak Struk</PrimaryButton>
                        <SecondaryButton @click="printThermal">Cetak Thermal</SecondaryButton>
                        <Link :href="route('pos.index')">
                            <PrimaryButton>← Kembali ke POS</PrimaryButton>
                        </Link>
                        <DangerButton v-if="!sale.voided_at && user.role === 'admin'" @click="voidSale">Void</DangerButton>
                        <Link :href="route('sales.index')">
                            <SecondaryButton>Kembali</SecondaryButton>
                        </Link>
                    </div>
                </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-8">
                        <!-- Receipt Header -->
                        <div class="mb-6 text-center border-b border-slate-200 pb-4">
                            <h3 class="text-lg font-bold">POS System</h3>
                            <p class="text-sm text-slate-500">Struk Penjualan</p>
                        </div>

                        <!-- Receipt Info -->
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

                        <!-- Items -->
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

                        <!-- Totals -->
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
                                <span class="text-slate-500">Pajak (11%)</span>
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
    .no-print { display: none; }
    .sm\:rounded-lg { box-shadow: none !important; }
    header { display: none !important; }
    nav { display: none !important; }
    .py-12 { padding-top: 0 !important; }
}
</style>
