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
})

const submit = () => {
    form.post(route('categories.store'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Tambah Kategori" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Tambah Kategori</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="max-w-md space-y-6">
                            <div>
                                <InputLabel for="name" value="Nama Kategori" />
                                <TextInput id="name" v-model="form.name" class="mt-1 block w-full" autofocus />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="flex items-center gap-3">
                                <PrimaryButton :processing="form.processing">Simpan</PrimaryButton>
                                <Link :href="route('categories.index')">
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
