<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
    stockIn: { type: Object, required: true },
})
</script>

<template>
    <Head title="Detail Barang Masuk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Detail Barang Masuk</h2>
                <Link :href="route('stock-ins.index')">
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
                                <span class="text-xs text-slate-500">Invoice</span>
                                <p class="font-mono font-medium">{{ stockIn.invoice_number }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500">Tanggal</span>
                                <p class="font-medium">{{ stockIn.created_at }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500">Supplier</span>
                                <p class="font-medium">{{ stockIn.supplier?.name }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500">Dibuat Oleh</span>
                                <p class="font-medium">{{ stockIn.creator?.name }}</p>
                            </div>
                            <div class="col-span-2">
                                <span class="text-xs text-slate-500">Catatan</span>
                                <p class="font-medium">{{ stockIn.notes || '-' }}</p>
                            </div>
                            <div v-if="stockIn.invoice_image" class="col-span-3">
                                <span class="text-xs text-slate-500">Invoice</span>
                                <a :href="`${$page.props.app.storage_url}/${stockIn.invoice_image}`" target="_blank" class="mt-1 inline-flex items-center gap-1 rounded-md bg-primary-50 px-3 py-1.5 text-sm font-medium text-primary-700 hover:bg-primary-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    Lihat Invoice
                                </a>
                            </div>
                        </div>

                        <div class="table-wrapper overflow-x-auto rounded-md border border-slate-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-slate-600">Produk</th>
                                        <th class="px-4 py-2 font-medium text-slate-600">SKU</th>
                                        <th class="px-4 py-2 font-medium text-slate-600">Qty</th>
                                        <th class="px-4 py-2 font-medium text-slate-600">Harga Beli</th>
                                        <th class="px-4 py-2 font-medium text-slate-600">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in stockIn.items" :key="item.id" class="border-t border-slate-100">
                                        <td class="px-4 py-2">{{ item.product?.name }}</td>
                                        <td class="px-4 py-2 text-xs text-slate-500">{{ item.product?.sku }}</td>
                                        <td class="px-4 py-2">{{ item.qty }}</td>
                                        <td class="px-4 py-2">Rp {{ Number(item.cost_price).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-2 font-medium">Rp {{ Number(item.subtotal).toLocaleString('id-ID') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-slate-50">
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-right font-medium text-slate-600">Total</td>
                                        <td class="px-4 py-2 font-bold">
                                            Rp {{ stockIn.items.reduce((s, i) => s + Number(i.subtotal), 0).toLocaleString('id-ID') }}
                                        </td>
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
