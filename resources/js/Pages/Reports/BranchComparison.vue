<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch } from 'vue'

const props = defineProps({
    branchData: { type: Array, default: () => [] },
    dateFrom: { type: String, default: '' },
    dateTo: { type: String, default: '' },
    filters: { type: Object, default: () => ({}) },
})

const dateFromVal = ref(props.dateFrom)
const dateToVal = ref(props.dateTo)

watch([dateFromVal, dateToVal], () => {
    router.get(route('reports.branch-comparison'), {
        date_from: dateFromVal.value,
        date_to: dateToVal.value,
    }, { preserveState: true, replace: true })
})

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`
</script>

<template>
    <Head title="Perbandingan Cabang" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Perbandingan Cabang</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card p-4 mb-6 flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-xs text-slate-600">Dari</label>
                        <TextInput v-model="dateFromVal" type="date" class="w-44" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-600">Sampai</label>
                        <TextInput v-model="dateToVal" type="date" class="w-44" />
                    </div>
                </div>

                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-4 py-3 font-medium text-slate-700">Cabang</th>
                                    <th class="px-4 py-3 text-right font-medium text-slate-700">Transaksi</th>
                                    <th class="px-4 py-3 text-right font-medium text-slate-700">Omzet</th>
                                    <th class="px-4 py-3 text-right font-medium text-slate-700">Laba Kotor</th>
                                    <th class="px-4 py-3 text-right font-medium text-slate-700">Margin Laba</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="b in branchData" :key="b.name" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium">{{ b.name }}</td>
                                    <td class="px-4 py-3 text-right">{{ b.transactions }}</td>
                                    <td class="px-4 py-3 text-right font-medium">{{ formatPrice(b.omzet) }}</td>
                                    <td class="px-4 py-3 text-right" :class="b.gross_profit >= 0 ? 'text-green-600' : 'text-red-600'">
                                        {{ formatPrice(b.gross_profit) }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium">{{ b.profit_margin }}%</td>
                                </tr>
                                <tr v-if="branchData.length === 0">
                                    <td colspan="5" class="py-6 text-center text-slate-500">Belum ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
