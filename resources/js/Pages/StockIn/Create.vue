<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
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

const form = useForm({
    supplier_id: '',
    invoice_number: '',
    invoice_image: null,
    notes: '',
    items: [],
})

const handleFileUpload = (e) => {
    form.invoice_image = e.target.files[0]
}

const productSearch = ref('')
const showProductDropdown = ref(false)

const closeDropdown = () => {
    setTimeout(() => showProductDropdown.value = false, 200)
}

const filteredProducts = computed(() => {
    if (!productSearch.value) return props.products.slice(0, 10)
    const q = productSearch.value.toLowerCase()
    return props.products.filter(
        (p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q) || (p.barcode && p.barcode.toLowerCase().includes(q)),
    ).slice(0, 10)
})

const addItem = (product) => {
    const existing = form.items.find((i) => i.product_id === product.id)
    if (existing) {
        existing.qty++
        existing.subtotal = existing.qty * existing.cost_price
    } else {
        form.items.push({
            product_id: product.id,
            name: product.name,
            sku: product.sku,
            qty: 1,
            cost_price: Number(product.cost_price) || 0,
            subtotal: Number(product.cost_price) || 0,
        })
    }
    productSearch.value = ''
    showProductDropdown.value = false
}

const removeItem = (index) => {
    form.items.splice(index, 1)
}

const updateSubtotal = (item) => {
    item.subtotal = item.qty * item.cost_price
}

const totalItems = computed(() => form.items.length)

const submit = () => {
    form.post(route('stock-ins.store'), {
        preserveScroll: true,
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Barang Masuk Baru" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Barang Masuk Baru</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid max-w-lg grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="supplier_id" value="Supplier" />
                                    <select id="supplier_id" v-model="form.supplier_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Pilih Supplier</option>
                                        <option v-for="s in suppliers" :key="s.id" :value="s.id">{{ s.name }}</option>
                                    </select>
                                    <InputError :message="form.errors.supplier_id" />
                                </div>
                                <div>
                                    <InputLabel for="invoice_number" value="No. Invoice" />
                                    <TextInput id="invoice_number" v-model="form.invoice_number" class="mt-1 block w-full" />
                                    <InputError :message="form.errors.invoice_number" />
                                </div>
                                <div>
                                    <InputLabel for="invoice_image" value="Upload Invoice" />
                                    <input id="invoice_image" type="file" accept="image/*,.pdf" @change="handleFileUpload" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-md file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100" />
                                    <InputError :message="form.errors.invoice_image" />
                                </div>
                            </div>

                            <div>
                                <InputLabel value="Cari Produk" />
                                <div class="relative">
                                    <TextInput v-model="productSearch" class="mt-1 block w-full" placeholder="Ketik nama, SKU, atau barcode..." @focus="showProductDropdown = true" @blur="closeDropdown" @keydown.escape="showProductDropdown = false" />
                                    <div v-if="showProductDropdown && filteredProducts.length" class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-md border border-slate-200 bg-white shadow-lg">
                                        <button v-for="p in filteredProducts" :key="p.id" type="button" @mousedown.prevent="addItem(p)" class="flex w-full items-center gap-3 px-4 py-2 text-left text-sm hover:bg-primary-50">
                                            <span class="font-medium">{{ p.name }}</span>
                                            <span class="text-xs text-slate-500">{{ p.sku }}</span>
                                            <span class="ml-auto text-xs text-slate-400">Stok: {{ p.stock }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.items.length">
                                <h4 class="mb-2 text-sm font-medium text-slate-700">Daftar Item ({{ totalItems }})</h4>
                                <div class="table-wrapper overflow-x-auto rounded-md border border-slate-200">
                                    <table class="w-full text-left text-sm">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-4 py-2 font-medium text-slate-600">Produk</th>
                                                <th class="px-4 py-2 font-medium text-slate-600">SKU</th>
                                                <th class="px-4 py-2 font-medium text-slate-600">Qty</th>
                                                <th class="px-4 py-2 font-medium text-slate-600">Harga Beli</th>
                                                <th class="px-4 py-2 font-medium text-slate-600">Subtotal</th>
                                                <th class="px-4 py-2 font-medium text-slate-600"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in form.items" :key="item.product_id" class="border-t border-slate-100">
                                                <td class="px-4 py-2">{{ item.name }}</td>
                                                <td class="px-4 py-2 text-xs text-slate-500">{{ item.sku }}</td>
                                                <td class="px-4 py-2">
                                                    <input v-model.number="item.qty" type="number" min="1" @input="updateSubtotal(item)" class="w-20 rounded border-slate-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500" />
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input v-model.number="item.cost_price" type="number" step="0.01" @input="updateSubtotal(item)" class="w-28 rounded border-slate-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500" />
                                                </td>
                                                <td class="px-4 py-2 font-medium">Rp {{ item.subtotal.toLocaleString('id-ID') }}</td>
                                                <td class="px-4 py-2">
                                                    <DangerButton type="button" @click="removeItem(index)">Hapus</DangerButton>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <InputError :message="form.errors.items" />
                            </div>

                            <div>
                                <InputLabel for="notes" value="Catatan" />
                                <textarea id="notes" v-model="form.notes" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" rows="3" />
                                <InputError :message="form.errors.notes" />
                            </div>

                            <div class="flex items-center gap-3 pt-4">
                                <PrimaryButton :processing="form.processing">Simpan</PrimaryButton>
                                <Link :href="route('stock-ins.index')">
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
