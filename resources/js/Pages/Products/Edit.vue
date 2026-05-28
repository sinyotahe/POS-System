<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import Checkbox from '@/Components/Checkbox.vue'
import { ref } from 'vue'

const props = defineProps({
    product: { type: Object, required: true },
    categories: { type: Array, required: true },
    branches: { type: Array, default: () => [] },
})

function skuPrefix(categoryId) {
    const cat = props.categories.find(c => c.id === Number(categoryId))
    if (!cat) return 'GEN'
    return cat.name.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase()
}

function generateSku(categoryId) {
    const prefix = skuPrefix(categoryId)
    const now = new Date()
    const y = now.getFullYear()
    const m = String(now.getMonth() + 1).padStart(2, '0')
    const d = String(now.getDate()).padStart(2, '0')
    const r = String(Math.floor(Math.random() * 900000) + 100000)
    return `${prefix}${y}${m}${d}${r}`
}

function generateBarcode() {
    const now = new Date()
    const y = now.getFullYear()
    const m = String(now.getMonth() + 1).padStart(2, '0')
    const d = String(now.getDate()).padStart(2, '0')
    const r = String(Math.floor(Math.random() * 900000) + 100000)
    return `${y}${m}${d}${r}`
}

const productBranchIds = (props.product.branches ?? []).map(b => b.id)

const form = useForm({
    category_id: props.product.category_id ?? '',
    sku: props.product.sku,
    barcode: props.product.barcode ?? '',
    name: props.product.name,
    cost_price: props.product.cost_price,
    sell_price: props.product.sell_price,
    minimum_stock: props.product.minimum_stock,
    image: null,
    status: Boolean(props.product.status),
    branch_ids: [...productBranchIds],
})

const imagePreview = ref(props.product.image ? `${usePage().props.app.storage_url}/${props.product.image}` : null)

const onImageChange = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.image = file
        const reader = new FileReader()
        reader.onload = (ev) => {
            imagePreview.value = ev.target.result
        }
        reader.readAsDataURL(file)
    }
}

const submit = () => {
    form.put(route('products.update', props.product.id), {
        preserveScroll: true,
        onSuccess: () => form.reset('image'),
    })
}
</script>

<template>
    <Head title="Edit Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-800">Edit Produk</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="max-w-lg space-y-6" enctype="multipart/form-data">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="name" value="Nama Produk" />
                                    <TextInput id="name" v-model="form.name" class="mt-1 block w-full" autofocus />
                                    <InputError :message="form.errors.name" />
                                </div>
                                <div>
                                    <InputLabel for="category_id" value="Kategori" />
                                    <select id="category_id" v-model="form.category_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">Pilih Kategori</option>
                                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                    </select>
                                    <InputError :message="form.errors.category_id" />
                                </div>
                                <div>
                                    <InputLabel for="sku" value="SKU" />
                                    <div class="mt-1 flex gap-2">
                                        <TextInput id="sku" v-model="form.sku" class="block flex-1" />
                                        <button type="button" @click="form.sku = generateSku(form.category_id)" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-500 hover:bg-slate-50 hover:text-slate-700" title="Generate ulang SKU">↻</button>
                                    </div>
                                    <InputError :message="form.errors.sku" />
                                </div>
                                <div>
                                    <InputLabel for="barcode" value="Barcode" />
                                    <div class="mt-1 flex gap-2">
                                        <TextInput id="barcode" v-model="form.barcode" class="block flex-1" />
                                        <button type="button" @click="form.barcode = generateBarcode()" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-500 hover:bg-slate-50 hover:text-slate-700" title="Generate ulang barcode">↻</button>
                                    </div>
                                    <InputError :message="form.errors.barcode" />
                                </div>
                                <div>
                                    <InputLabel for="cost_price" value="Harga Modal" />
                                    <TextInput id="cost_price" v-model="form.cost_price" type="number" step="0.01" class="mt-1 block w-full" />
                                    <InputError :message="form.errors.cost_price" />
                                </div>
                                <div>
                                    <InputLabel for="sell_price" value="Harga Jual" />
                                    <TextInput id="sell_price" v-model="form.sell_price" type="number" step="0.01" class="mt-1 block w-full" />
                                    <InputError :message="form.errors.sell_price" />
                                </div>
                                <div>
                                    <InputLabel for="minimum_stock" value="Minimum Stok" />
                                    <TextInput id="minimum_stock" v-model="form.minimum_stock" type="number" class="mt-1 block w-full" />
                                    <InputError :message="form.errors.minimum_stock" />
                                </div>
                                <div>
                                    <InputLabel for="image" value="Gambar" />
                                    <input id="image" type="file" @change="onImageChange" accept="image/*" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-md file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100" />
                                    <InputError :message="form.errors.image" />
                                </div>
                            </div>

                            <div v-if="imagePreview" class="mt-2">
                                <img :src="imagePreview" class="h-32 w-32 rounded object-cover shadow" />
                            </div>

                            <div class="flex items-center gap-2">
                                <Checkbox id="status" v-model:checked="form.status" />
                                <label for="status" class="text-sm text-slate-600">Produk aktif</label>
                                <InputError :message="form.errors.status" />
                            </div>

                            <div v-if="branches.length > 0">
                                <InputLabel value="Tersedia di Cabang" />
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    <label v-for="branch in branches" :key="branch.id" class="flex items-center gap-2 rounded border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50 cursor-pointer">
                                        <Checkbox :value="branch.id" v-model:checked="form.branch_ids" />
                                        {{ branch.name }}
                                    </label>
                                </div>
                                <InputError :message="form.errors.branch_ids" />
                            </div>

                            <div class="flex items-center gap-3 pt-4">
                                <PrimaryButton :processing="form.processing">Simpan</PrimaryButton>
                                <Link :href="route('products.index')">
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
