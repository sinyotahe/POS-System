<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const form = useForm({
    name: '',
    phone: '',
    address: '',
})

const submit = () => {
    form.post(route('suppliers.store'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Tambah Supplier" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Tambah Supplier</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="max-w-lg space-y-6">
                            <div>
                                <InputLabel for="name" value="Nama Supplier" />
                                <TextInput id="name" v-model="form.name" class="mt-1 block w-full" autofocus />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div>
                                <InputLabel for="phone" value="Telepon" />
                                <TextInput id="phone" v-model="form.phone" class="mt-1 block w-full" />
                                <InputError :message="form.errors.phone" />
                            </div>
                            <div>
                                <InputLabel for="address" value="Alamat" />
                                <textarea id="address" v-model="form.address" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" rows="3" />
                                <InputError :message="form.errors.address" />
                            </div>
                            <div class="flex items-center gap-3">
                                <PrimaryButton :processing="form.processing">Simpan</PrimaryButton>
                                <Link :href="route('suppliers.index')">
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
