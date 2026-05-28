<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

defineProps({
    transfers: { type: Object, required: true },
})
</script>

<template>
    <Head title="Transfer Stok" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Transfer Stok</h2>
                <Link :href="route('stock-transfers.create')">
                    <PrimaryButton>Transfer Baru</PrimaryButton>
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
                                    <th class="py-3 font-medium text-slate-700">Dari Cabang</th>
                                    <th class="py-3 font-medium text-slate-700">Ke Cabang</th>
                                    <th class="py-3 font-medium text-slate-700">Produk</th>
                                    <th class="py-3 font-medium text-slate-700">Jumlah</th>
                                    <th class="py-3 font-medium text-slate-700">Keterangan</th>
                                    <th class="py-3 font-medium text-slate-700">Dibuat Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="t in transfers.data ?? []" :key="t.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3">{{ t.created_at }}</td>
                                    <td class="py-3">{{ t.from_branch?.name }}</td>
                                    <td class="py-3">{{ t.to_branch?.name }}</td>
                                    <td class="py-3">{{ t.product?.name }}</td>
                                    <td class="py-3 font-semibold">{{ t.qty }}</td>
                                    <td class="py-3 text-slate-500">{{ t.notes || '-' }}</td>
                                    <td class="py-3">{{ t.creator?.name }}</td>
                                </tr>
                                <tr v-if="(transfers.data ?? []).length === 0">
                                    <td colspan="7" class="py-6 text-center text-slate-500">Belum ada transfer stok</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="transfers.links" class="flex justify-center gap-1 p-4">
                        <component :is="'a'" v-for="(link, i) in transfers.links" :key="i"
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
