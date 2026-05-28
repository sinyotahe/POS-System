<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    logs: { type: Object, required: true },
})

const actionLabels = {
    create: 'Membuat',
    update: 'Mengubah',
    delete: 'Menghapus',
    void: 'Membatalkan',
}

const subjectLabels = {
    product: 'Produk',
    category: 'Kategori',
    supplier: 'Supplier',
    user: 'Pengguna',
    sale: 'Penjualan',
    setting: 'Pengaturan',
}
</script>

<template>
    <Head title="Aktivitas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Aktivitas</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-3 font-medium text-slate-700">Waktu</th>
                                    <th class="py-3 font-medium text-slate-700">User</th>
                                    <th class="py-3 font-medium text-slate-700">Aksi</th>
                                    <th class="py-3 font-medium text-slate-700">Subjek</th>
                                    <th class="py-3 font-medium text-slate-700">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="log in logs.data ?? []" :key="log.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3 text-slate-500">{{ log.created_at }}</td>
                                    <td class="py-3 font-medium">{{ log.user?.name }}</td>
                                    <td class="py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium"
                                            :class="log.action === 'create' ? 'bg-green-100 text-green-700' : log.action === 'update' ? 'bg-blue-100 text-blue-700' : log.action === 'delete' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700'"
                                        >
                                            {{ actionLabels[log.action] || log.action }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ subjectLabels[log.subject_type] || log.subject_type }}</td>
                                    <td class="py-3 text-slate-500">{{ log.description }}</td>
                                </tr>
                                <tr v-if="(logs.data ?? []).length === 0">
                                    <td colspan="5" class="py-6 text-center text-slate-500">Belum ada aktivitas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="logs.links" class="flex justify-center gap-1 p-4">
                        <component :is="'a'" v-for="(link, i) in logs.links" :key="i"
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
