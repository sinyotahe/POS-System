<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
    user: { type: Object, required: true },
    branches: { type: Array, default: () => [] },
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    role: props.user.role,
    branch_id: props.user.branch_id ?? '',
})

const submit = () => {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Edit User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Edit User</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="max-w-md space-y-6">
                            <div>
                                <InputLabel for="name" value="Nama" />
                                <TextInput id="name" v-model="form.name" class="mt-1 block w-full" autofocus />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div>
                                <InputLabel for="password" value="Password (biarkan kosong jika tidak diubah)" />
                                <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div>
                                <InputLabel for="role" value="Role" />
                                <select id="role" v-model="form.role" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="admin">Admin</option>
                                    <option value="kasir">Kasir</option>
                                    <option value="owner">Owner</option>
                                </select>
                                <InputError :message="form.errors.role" />
                            </div>

                            <div>
                                <InputLabel for="branch_id" value="Cabang" />
                                <select id="branch_id" v-model="form.branch_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Pilih Cabang</option>
                                    <option v-for="branch in branches" :key="branch.id" :value="branch.id">{{ branch.name }}</option>
                                </select>
                                <InputError :message="form.errors.branch_id" />
                            </div>

                            <div class="flex items-center gap-3">
                                <PrimaryButton :processing="form.processing">Simpan</PrimaryButton>
                                <Link :href="route('users.index')">
                                    <SecondaryButton>Kembali</SecondaryButton>
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
