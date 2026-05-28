<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import Checkbox from '@/Components/Checkbox.vue'

const tabClass = (isActive) =>
    isActive ? 'rounded-md bg-white px-4 py-2 text-sm font-medium text-slate-800 shadow-sm' : 'rounded-md px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700'

const props = defineProps({
    tax_rate: { type: Number, required: true },
    whatsapp_api_key: { type: String, default: '' },
    whatsapp_phone: { type: String, default: '' },
    whatsapp_notify_low_stock: { type: Boolean, default: false },
    whatsapp_notify_daily_report: { type: Boolean, default: false },
    whatsapp_notify_sale_receipt: { type: Boolean, default: false },
})

const form = useForm({
    tax_rate: Math.round(props.tax_rate * 100),
    whatsapp_api_key: props.whatsapp_api_key,
    whatsapp_phone: props.whatsapp_phone,
    whatsapp_notify_low_stock: props.whatsapp_notify_low_stock,
    whatsapp_notify_daily_report: props.whatsapp_notify_daily_report,
    whatsapp_notify_sale_receipt: props.whatsapp_notify_sale_receipt,
})

const submit = () => {
    form.put(route('settings.update'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Pengaturan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Pengaturan</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 flex gap-1 rounded-lg bg-slate-100 p-1">
                    <Link :href="route('users.index')" :class="tabClass(route().current('users.*'))">Pengguna</Link>
                    <Link :href="route('branches.index')" :class="tabClass(route().current('branches.*'))">Cabang</Link>
                    <Link :href="route('settings.edit')" :class="tabClass(route().current('settings.*'))">Pengaturan</Link>
                </div>

                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="max-w-xl space-y-6">
                            <h3 class="text-lg font-medium text-slate-900 border-b pb-2">Pajak</h3>
                            <div>
                                <InputLabel for="tax_rate" value="Tarif Pajak (%)" />
                                <TextInput id="tax_rate" v-model="form.tax_rate" type="number" min="0" max="100" class="mt-1 block w-full" />
                                <p class="mt-1 text-xs text-slate-500">Persentase pajak yang diterapkan pada transaksi POS. 0 untuk nonaktif.</p>
                                <InputError :message="form.errors.tax_rate" />
                            </div>

                            <h3 class="text-lg font-medium text-slate-900 border-b pb-2 pt-4">WhatsApp</h3>
                            <p class="text-sm text-slate-500">Konfigurasi API WhatsApp untuk notifikasi otomatis. Gunakan API key dari Fonnte atau provider lain.</p>

                            <div>
                                <InputLabel for="whatsapp_api_key" value="API Key / Token" />
                                <TextInput id="whatsapp_api_key" v-model="form.whatsapp_api_key" type="password" class="mt-1 block w-full" placeholder="Masukkan API key..." />
                                <InputError :message="form.errors.whatsapp_api_key" />
                            </div>

                            <div>
                                <InputLabel for="whatsapp_phone" value="Nomor Tujuan (Owner)" />
                                <TextInput id="whatsapp_phone" v-model="form.whatsapp_phone" type="text" class="mt-1 block w-full" placeholder="6281234567890" />
                                <p class="mt-1 text-xs text-slate-500">Nomor WhatsApp owner untuk menerima laporan dan notifikasi. Format: 628xxx (tanpa +).</p>
                                <InputError :message="form.errors.whatsapp_phone" />
                            </div>

                            <div class="space-y-3">
                                <label class="flex items-center gap-3">
                                    <Checkbox v-model:checked="form.whatsapp_notify_low_stock" />
                                    <span class="text-sm text-slate-700">Notifikasi stok minimum</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <Checkbox v-model:checked="form.whatsapp_notify_daily_report" />
                                    <span class="text-sm text-slate-700">Laporan harian (otomatis jam 20:00)</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <Checkbox v-model:checked="form.whatsapp_notify_sale_receipt" />
                                    <span class="text-sm text-slate-700">Kirim struk via WhatsApp setelah transaksi</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-3 pt-4">
                                <PrimaryButton :processing="form.processing">Simpan</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
