<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import Modal from '@/Components/Modal.vue'
import { ref } from 'vue'

const props = defineProps({
    suppliers: { type: Object, required: true },
})

const confirmDelete = ref(null)
const deleteForm = useForm({})

const destroy = (id) => {
    deleteForm.delete(route('suppliers.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            confirmDelete.value = null
        },
    })
}
</script>

<template>
    <Head title="Supplier" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Supplier</h2>
                <Link :href="route('suppliers.create')">
                    <PrimaryButton>Tambah Supplier</PrimaryButton>
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
                                    <th class="py-3 font-medium text-slate-700">Nama</th>
                                    <th class="py-3 font-medium text-slate-700">Telepon</th>
                                    <th class="py-3 font-medium text-slate-700">Alamat</th>
                                    <th class="py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="supplier in suppliers.data ?? []" :key="supplier.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3 font-medium">{{ supplier.name }}</td>
                                    <td class="py-3">{{ supplier.phone }}</td>
                                    <td class="py-3 max-w-xs truncate">{{ supplier.address }}</td>
                                    <td class="py-3">
                                        <div class="flex gap-2">
                                            <Link :href="route('suppliers.edit', supplier.id)">
                                                <SecondaryButton>Edit</SecondaryButton>
                                            </Link>
                                            <DangerButton @click="confirmDelete = supplier">Hapus</DangerButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="(suppliers.data ?? []).length === 0">
                                    <td colspan="4" class="py-6 text-center text-slate-500">Belum ada supplier</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="suppliers.links" class="flex justify-center gap-1 p-4">
                        <component :is="'a'" v-for="(link, i) in suppliers.links" :key="i"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="rounded-md px-3 py-1 text-sm"
                            :class="link.active ? 'bg-primary-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100'"
                        />
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="confirmDelete !== null" @close="confirmDelete = null">
            <div class="p-6">
                <h3 class="text-lg font-medium text-slate-900">Hapus Supplier</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Apakah Anda yakin ingin menghapus supplier <strong>{{ confirmDelete?.name }}</strong>?
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmDelete = null">Batal</SecondaryButton>
                    <DangerButton @click="destroy(confirmDelete.id)" :disabled="deleteForm.processing">Hapus</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
