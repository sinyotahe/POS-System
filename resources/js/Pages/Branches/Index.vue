<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import Modal from '@/Components/Modal.vue'
import { ref, watch } from 'vue'

const tabClass = (isActive) =>
    isActive ? 'rounded-md bg-white px-4 py-2 text-sm font-medium text-slate-800 shadow-sm' : 'rounded-md px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700'

const props = defineProps({
    branches: { type: Object, required: true },
    filters: { type: Object, default: () => ({ search: '' }) },
})

const search = ref(props.filters.search ?? '')
const confirmDelete = ref(null)
const deleteForm = useForm({})

watch(search, (val) => {
    router.get(route('branches.index'), { search: val }, { preserveState: true, replace: true })
})

const destroy = (id) => {
    deleteForm.delete(route('branches.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            confirmDelete.value = null
        },
    })
}
</script>

<template>
    <Head title="Cabang" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Cabang</h2>
                <Link :href="route('branches.create')">
                    <PrimaryButton>Tambah Cabang</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Tab Navigation -->
                <div class="mb-6 flex gap-1 rounded-lg bg-slate-100 p-1">
                    <Link :href="route('users.index')" :class="tabClass(route().current('users.*'))">Pengguna</Link>
                    <Link :href="route('branches.index')" :class="tabClass(route().current('branches.*'))">Cabang</Link>
                    <Link :href="route('settings.edit')" :class="tabClass(route().current('settings.*'))">Pengaturan</Link>
                </div>

                <div class="card p-4 mb-6">
                    <TextInput v-model="search" placeholder="Cari nama atau kode..." class="w-72" />
                </div>

                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-4 py-3 font-medium text-slate-700">Kode</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Nama</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Telepon</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Jumlah User</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Jumlah Produk</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Status</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="branch in branches.data ?? []" :key="branch.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3 font-mono text-xs">{{ branch.code }}</td>
                                    <td class="px-4 py-3 font-medium">{{ branch.name }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ branch.phone || '-' }}</td>
                                    <td class="px-4 py-3">{{ branch.users_count }}</td>
                                    <td class="px-4 py-3">{{ branch.products_count ?? 0 }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="branch.status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                            {{ branch.status ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <Link :href="route('branches.edit', branch.id)">
                                                <SecondaryButton>Edit</SecondaryButton>
                                            </Link>
                                            <DangerButton @click="confirmDelete = branch">Hapus</DangerButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="(branches.data ?? []).length === 0">
                                    <td colspan="6" class="py-6 text-center text-slate-500">Belum ada cabang</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="branches.links" class="mt-4 flex justify-center gap-1">
                    <component :is="'a'" v-for="(link, i) in branches.links" :key="i"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="rounded-md px-3 py-1 text-sm"
                        :class="link.active ? 'bg-primary-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100'"
                    />
                </div>
            </div>
        </div>

        <Modal :show="confirmDelete !== null" @close="confirmDelete = null">
            <div class="p-6">
                <h3 class="text-lg font-medium text-slate-900">Hapus Cabang</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Apakah Anda yakin ingin menghapus cabang <strong>{{ confirmDelete?.name }}</strong> ({{ confirmDelete?.code }})?
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmDelete = null">Batal</SecondaryButton>
                    <DangerButton @click="destroy(confirmDelete.id)" :disabled="deleteForm.processing">Hapus</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
