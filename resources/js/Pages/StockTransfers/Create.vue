<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import { computed } from 'vue'

const props = defineProps({
    branches: { type: Array, required: true },
    products: { type: Array, default: () => [] },
    currentBranchId: { type: Number, default: null },
})

const form = useForm({
    to_branch_id: '',
    product_id: '',
    qty: 1,
    notes: '',
})

const selectedProduct = computed(() => {
    return props.products.find(p => p.id === Number(form.product_id))
})

const currentBranchName = computed(() => {
    const b = props.branches.find(b => b.id === props.currentBranchId)
    return b?.name || 'Cabang saat ini'
})

function submit() {
    form.post(route('stock-transfers.store'))
}
</script>

<template>
    <Head title="Transfer Stok Baru" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Transfer Stok Baru</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="card p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel value="Cabang Asal" />
                            <p class="mt-1 font-semibold text-slate-700">{{ currentBranchName }}</p>
                        </div>

                        <div>
                            <InputLabel for="to_branch_id" value="Cabang Tujuan" />
                            <select id="to_branch_id" v-model="form.to_branch_id"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Pilih Cabang</option>
                                <option v-for="b in branches.filter(b => b.id !== currentBranchId)" :key="b.id" :value="b.id">
                                    {{ b.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.to_branch_id" />
                        </div>

                        <div>
                            <InputLabel for="product_id" value="Produk" />
                            <select id="product_id" v-model="form.product_id"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Pilih Produk</option>
                                <option v-for="p in products" :key="p.id" :value="p.id">
                                    {{ p.name }} (SKU: {{ p.sku }}) — Stok: {{ p.stock }}
                                </option>
                            </select>
                            <InputError :message="form.errors.product_id" />
                            <p v-if="selectedProduct" class="mt-1 text-xs text-slate-500">
                                Stok tersedia di cabang asal: <strong>{{ selectedProduct.stock }}</strong>
                            </p>
                        </div>

                        <div>
                            <InputLabel for="qty" value="Jumlah Transfer" />
                            <TextInput id="qty" v-model="form.qty" type="number" min="1"
                                :max="selectedProduct?.stock ?? 1"
                                class="mt-1 block w-full" />
                            <InputError :message="form.errors.qty" />
                        </div>

                        <div>
                            <InputLabel for="notes" value="Keterangan (opsional)" />
                            <textarea id="notes" v-model="form.notes" rows="2"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                placeholder="Alasan transfer..." />
                            <InputError :message="form.errors.notes" />
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Simpan Transfer</PrimaryButton>
                            <Link :href="route('stock-transfers.index')">
                                <SecondaryButton>Kembali</SecondaryButton>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
