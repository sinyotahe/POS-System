<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import { ref, computed } from 'vue'

const props = defineProps({
    suppliers: { type: Array, required: true },
    products: { type: Array, required: true },
})

const selectedItems = ref([])

const addItem = () => {
    selectedItems.value.push({ product_id: '', qty: 1, cost_price: 0 })
}

const removeItem = (index) => {
    selectedItems.value.splice(index, 1)
}

const availableProducts = ref(props.products)

const getProduct = (productId) => {
    return availableProducts.value.find((p) => p.id === productId)
}

const total = computed(() => {
    return selectedItems.value.reduce((sum, item) => {
        return sum + (item.qty * item.cost_price)
    }, 0)
})

const form = useForm({
    supplier_id: '',
    notes: '',
})

const submit = () => {
    form.transform(() => ({
        supplier_id: form.supplier_id,
        notes: form.notes,
        items: selectedItems.value.map((item) => ({
            product_id: item.product_id,
            qty: item.qty,
            cost_price: item.cost_price,
        })),
    })).post(route('purchase-orders.store'), {
        preserveScroll: true,
    })
}

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`
</script>

<template>
    <Head title="Buat Purchase Order" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Buat Purchase Order</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="supplier" value="Supplier" />
                                    <select id="supplier" v-model="form.supplier_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Pilih Supplier</option>
                                        <option v-for="s in suppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                                    </select>
                                    <InputError :message="form.errors.supplier_id" />
                                </div>
                                <div>
                                    <InputLabel for="notes" value="Catatan" />
                                    <textarea id="notes" v-model="form.notes" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" rows="2"></textarea>
                                    <InputError :message="form.errors.notes" />
                                </div>
                            </div>

                            <!-- Items -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-medium text-slate-700">Item Produk</h3>
                                    <SecondaryButton type="button" @click="addItem">+ Tambah Item</SecondaryButton>
                                </div>

                                <div v-if="selectedItems.length === 0" class="py-6 text-center text-sm text-slate-400">
                                    Belum ada item. Klik "Tambah Item" untuk mulai.
                                </div>

                                <div v-for="(item, index) in selectedItems" :key="index" class="mb-3 rounded-lg border border-slate-200 p-4">
                                    <div class="grid grid-cols-12 gap-3 items-end">
                                        <div class="col-span-5">
                                            <label class="block text-xs text-slate-600">Produk</label>
                                            <select v-model="item.product_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                                <option value="">Pilih Produk</option>
                                                <option v-for="p in availableProducts" :key="p.id" :value="p.id">{{ p.name }} ({{ p.sku }})</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-xs text-slate-600">Qty</label>
                                            <TextInput v-model.number="item.qty" type="number" min="1" class="mt-1 block w-full" />
                                        </div>
                                        <div class="col-span-3">
                                            <label class="block text-xs text-slate-600">Harga Beli</label>
                                            <TextInput v-model.number="item.cost_price" type="number" min="0" class="mt-1 block w-full" @update:model-value="val => { if (getProduct(item.product_id)) { /* manual entry */ } }" />
                                        </div>
                                        <div class="col-span-1 text-right">
                                            <p class="text-xs text-slate-500">{{ item.qty && item.cost_price ? formatPrice(item.qty * item.cost_price) : 'Rp 0' }}</p>
                                        </div>
                                        <div class="col-span-1">
                                            <DangerButton type="button" @click="removeItem(index)" class="w-full justify-center text-xs px-2 py-1">X</DangerButton>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="selectedItems.length > 0" class="border-t border-slate-200 pt-3 text-right">
                                    <p class="text-lg font-bold">Total: {{ formatPrice(total) }}</p>
                                </div>
                            </div>

                            <InputError :message="form.errors.items" />

                            <div class="flex items-center gap-3 border-t border-slate-200 pt-4">
                                <PrimaryButton :processing="form.processing">Simpan sebagai Draft</PrimaryButton>
                                <Link :href="route('purchase-orders.index')">
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
