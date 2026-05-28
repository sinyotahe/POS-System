<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import { ref, computed } from 'vue'

const props = defineProps({
    products: { type: Array, required: true },
})

const form = useForm({
    product_id: '',
    qty: '',
    reason: '',
})

const productSearch = ref('')
const showProductDropdown = ref(false)
const selectedProduct = ref(null)

const closeDropdown = () => {
    setTimeout(() => showProductDropdown.value = false, 200)
}

const filteredProducts = computed(() => {
    if (!productSearch.value) return props.products.slice(0, 10)
    const q = productSearch.value.toLowerCase()
    return props.products.filter(
        (p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q),
    ).slice(0, 10)
})

const selectProduct = (product) => {
    selectedProduct.value = product
    form.product_id = product.id
    productSearch.value = product.name
    showProductDropdown.value = false
}

const submit = () => {
    form.post(route('stock-adjustments.store'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Penyesuaian Stok" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Penyesuaian Stok</h2>
                <Link :href="route('stock-movements.index')">
                    <SecondaryButton>Kembali</SecondaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel value="Produk" />
                                <div class="relative">
                                    <TextInput v-model="productSearch" class="mt-1 block w-full" placeholder="Ketik nama atau SKU..." @focus="showProductDropdown = true" @blur="closeDropdown" @keydown.escape="showProductDropdown = false" />
                                    <div v-if="showProductDropdown && filteredProducts.length" class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-md border border-slate-200 bg-white shadow-lg">
                                        <button v-for="p in filteredProducts" :key="p.id" type="button" @mousedown.prevent="selectProduct(p)" class="flex w-full items-center gap-3 px-4 py-2 text-left text-sm hover:bg-primary-50">
                                            <span class="font-medium">{{ p.name }}</span>
                                            <span class="text-xs text-slate-500">{{ p.sku }}</span>
                                            <span class="ml-auto text-xs text-slate-400">Stok: {{ p.stock }}</span>
                                        </button>
                                    </div>
                                </div>
                                <p v-if="selectedProduct" class="mt-1 text-xs text-slate-500">Stok saat ini: <strong>{{ selectedProduct.stock }}</strong></p>
                                <InputError :message="form.errors.product_id" />
                            </div>

                            <div>
                                <InputLabel for="qty" value="Jumlah (+/-)" />
                                <TextInput id="qty" v-model.number="form.qty" type="number" class="mt-1 block w-48" placeholder="Gunakan + atau -" />
                                <p class="mt-1 text-xs text-slate-500">Gunakan angka positif untuk menambah stok, negatif untuk mengurangi.</p>
                                <InputError :message="form.errors.qty" />
                            </div>

                            <div>
                                <InputLabel for="reason" value="Alasan" />
                                <textarea id="reason" v-model="form.reason" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" rows="3" placeholder="Alasan penyesuaian stok..." />
                                <InputError :message="form.errors.reason" />
                            </div>

                            <div class="flex items-center gap-3 pt-4">
                                <PrimaryButton :processing="form.processing">Simpan</PrimaryButton>
                                <Link :href="route('stock-movements.index')">
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
