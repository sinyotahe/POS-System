<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    sales: { type: Object, required: true },
    cashiers: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ date_from: '', date_to: '', payment_method: '', cashier_id: '' }) },
})

const userRole = usePage().props.auth.user.role

const dateFrom = ref(props.filters.date_from ?? '')
const dateTo = ref(props.filters.date_to ?? '')
const paymentMethod = ref(props.filters.payment_method ?? '')
const cashierFilter = ref(props.filters.cashier_id ?? '')

watch([dateFrom, dateTo, paymentMethod, cashierFilter], () => {
    router.get(route('sales.index'), {
        date_from: dateFrom.value,
        date_to: dateTo.value,
        payment_method: paymentMethod.value,
        cashier_id: cashierFilter.value,
    }, { preserveState: true, replace: true })
})

const paymentLabels = {
    cash: 'Tunai',
    transfer: 'Transfer',
    qris: 'QRIS',
    'e-wallet': 'E-Wallet',
}

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`
</script>

<template>
    <Head title="Penjualan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Penjualan</h2>
                <Link v-if="userRole !== 'owner'" :href="route('pos.index')">
                    <PrimaryButton>+ Transaksi Baru</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card p-4 mb-6 flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-xs text-slate-600">Dari</label>
                        <TextInput v-model="dateFrom" type="date" class="mt-1 w-44" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Sampai</label>
                        <TextInput v-model="dateTo" type="date" class="mt-1 w-44" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Pembayaran</label>
                        <select v-model="paymentMethod" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua</option>
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>
                    <div v-if="userRole !== 'kasir'">
                        <label class="block text-xs text-slate-600">Kasir</label>
                        <select v-model="cashierFilter" class="mt-1 rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Semua Kasir</option>
                            <option v-for="c in cashiers" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-3 font-medium text-slate-700">Invoice</th>
                                    <th class="py-3 font-medium text-slate-700">Tanggal</th>
                                    <th class="py-3 font-medium text-slate-700">Pelanggan</th>
                                    <th class="py-3 font-medium text-slate-700">Total</th>
                                    <th class="py-3 font-medium text-slate-700">Pembayaran</th>
                                    <th class="py-3 font-medium text-slate-700">Kasir</th>
                                    <th class="py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sale in sales.data ?? []" :key="sale.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3 font-mono text-xs font-medium">{{ sale.invoice_number }}</td>
                                    <td class="py-3">{{ sale.created_at }}</td>
                                    <td class="py-3">{{ sale.customer_name || '-' }}</td>
                                    <td class="py-3 font-medium">{{ formatPrice(sale.grand_total) }}</td>
                                    <td class="py-3">
                                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">
                                            {{ paymentLabels[sale.payment_method] || sale.payment_method }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-slate-500">{{ sale.cashier?.name }}</td>
                                    <td class="py-3">
                                        <Link :href="route('sales.show', sale.id)">
                                            <SecondaryButton>Detail</SecondaryButton>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="(sales.data ?? []).length === 0">
                                    <td colspan="7" class="py-6 text-center text-slate-500">Belum ada penjualan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="sales.links" class="mt-4 flex justify-center gap-1">
                        <component :is="'a'" v-for="(link, i) in sales.links" :key="i"
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
