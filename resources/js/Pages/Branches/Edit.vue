<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
    branch: { type: Object, required: true },
})

const form = useForm({
    name: props.branch.name,
    code: props.branch.code,
    address: props.branch.address || '',
    phone: props.branch.phone || '',
    email: props.branch.email || '',
    status: props.branch.status,
})

const submit = () => {
    form.put(route('branches.update', props.branch.id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Edit Cabang" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Edit Cabang</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="max-w-md space-y-6">
                            <div>
                                <InputLabel for="code" value="Kode Cabang" />
                                <TextInput id="code" v-model="form.code" class="mt-1 block w-full" autofocus />
                                <InputError :message="form.errors.code" />
                            </div>

                            <div>
                                <InputLabel for="name" value="Nama Cabang" />
                                <TextInput id="name" v-model="form.name" class="mt-1 block w-full" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="address" value="Alamat" />
                                <textarea id="address" v-model="form.address" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" rows="3" />
                                <InputError :message="form.errors.address" />
                            </div>

                            <div>
                                <InputLabel for="phone" value="Telepon" />
                                <TextInput id="phone" v-model="form.phone" class="mt-1 block w-full" />
                                <InputError :message="form.errors.phone" />
                            </div>

                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" v-model="form.status" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500" />
                                    <span class="text-sm text-slate-700">Aktif</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-3">
                                <PrimaryButton :processing="form.processing">Simpan</PrimaryButton>
                                <Link :href="route('branches.index')">
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
