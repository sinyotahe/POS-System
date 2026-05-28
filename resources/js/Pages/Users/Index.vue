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
    users: { type: Object, required: true },
    filters: { type: Object, default: () => ({ search: '' }) },
})

const search = ref(props.filters.search ?? '')
const confirmDelete = ref(null)
const deleteForm = useForm({})

watch(search, (val) => {
    router.get(route('users.index'), { search: val }, { preserveState: true, replace: true })
})

const destroy = (id) => {
    deleteForm.delete(route('users.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            confirmDelete.value = null
        },
    })
}

const roleLabels = {
    admin: 'Admin',
    kasir: 'Kasir',
    owner: 'Owner',
}

const roleColors = {
    admin: 'bg-purple-100 text-purple-700',
    kasir: 'bg-blue-100 text-blue-700',
    owner: 'bg-amber-100 text-amber-700',
}
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Users</h2>
                <Link :href="route('users.create')">
                    <PrimaryButton>Tambah User</PrimaryButton>
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
                    <TextInput v-model="search" placeholder="Cari nama atau email..." class="w-72" />
                </div>

                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="px-4 py-3 font-medium text-slate-700">Nama</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Email</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Cabang</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Role</th>
                                    <th class="px-4 py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data ?? []" :key="user.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ user.email }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ user.branch?.name || '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="roleColors[user.role] || 'bg-slate-100 text-slate-700'">
                                            {{ roleLabels[user.role] || user.role }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <Link :href="route('users.edit', user.id)">
                                                <SecondaryButton>Edit</SecondaryButton>
                                            </Link>
                                            <DangerButton @click="confirmDelete = user" :disabled="user.id === $page.props.auth.user.id">Hapus</DangerButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="(users.data ?? []).length === 0">
                                    <td colspan="5" class="py-6 text-center text-slate-500">Belum ada user</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="users.links" class="mt-4 flex justify-center gap-1">
                    <component :is="'a'" v-for="(link, i) in users.links" :key="i"
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
                <h3 class="text-lg font-medium text-slate-900">Hapus User</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Apakah Anda yakin ingin menghapus user <strong>{{ confirmDelete?.name }}</strong> ({{ confirmDelete?.email }})?
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmDelete = null">Batal</SecondaryButton>
                    <DangerButton @click="destroy(confirmDelete.id)" :disabled="deleteForm.processing">Hapus</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
