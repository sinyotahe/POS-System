<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import Modal from '@/Components/Modal.vue'
import { ref } from 'vue'

const props = defineProps({
    categories: { type: Object, required: true },
})

const confirmDelete = ref(null)
const deleteForm = useForm({})

const destroy = (id) => {
    deleteForm.delete(route('categories.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            confirmDelete.value = null
        },
    })
}
</script>

<template>
    <Head title="Kategori" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Kategori</h2>
                <Link :href="route('categories.create')">
                    <PrimaryButton>Tambah Kategori</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="table-wrapper overflow-x-auto p-6">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-3 font-medium text-slate-700">Nama</th>
                                    <th class="py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in categories.data ?? []" :key="category.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3">{{ category.name }}</td>
                                    <td class="py-3">
                                        <div class="flex gap-2">
                                            <Link :href="route('categories.edit', category.id)">
                                                <SecondaryButton>Edit</SecondaryButton>
                                            </Link>
                                            <DangerButton @click="confirmDelete = category">Hapus</DangerButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="(categories.data ?? []).length === 0">
                                    <td colspan="2" class="py-6 text-center text-slate-500">Belum ada kategori</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="categories.links" class="flex justify-center gap-1 p-4">
                        <component :is="'a'" v-for="(link, i) in categories.links" :key="i"
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
                <h3 class="text-lg font-medium text-slate-900">Hapus Kategori</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Apakah Anda yakin ingin menghapus kategori <strong>{{ confirmDelete?.name }}</strong>?
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmDelete = null">Batal</SecondaryButton>
                    <DangerButton @click="destroy(confirmDelete.id)" :disabled="deleteForm.processing">Hapus</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
