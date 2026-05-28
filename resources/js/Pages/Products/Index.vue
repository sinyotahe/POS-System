<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import TextInput from '@/Components/TextInput.vue'
import Modal from '@/Components/Modal.vue'
import { ref, watch } from 'vue'

const props = defineProps({
    products: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ search: '', category_id: '', status: '' }) },
})

const confirmDelete = ref(null)
const deleteForm = useForm({})

const destroy = (id) => {
    deleteForm.delete(route('products.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            confirmDelete.value = null
        },
    })
}

const search = ref(props.filters.search ?? '')
const categoryFilter = ref(props.filters.category_id ?? '')
const statusFilter = ref(props.filters.status ?? '')

let debounceTimer
watch(search, (val) => {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => {
        router.get(route('products.index'), { search: val, category_id: categoryFilter.value, status: statusFilter.value }, { preserveState: true, replace: true })
    }, 400)
})

watch([categoryFilter, statusFilter], () => {
    router.get(route('products.index'), { search: search.value, category_id: categoryFilter.value, status: statusFilter.value }, { preserveState: true, replace: true })
})

const toggleStatus = (product) => {
    router.patch(route('products.toggle-status', product.id), {}, { preserveScroll: true })
}
</script>

<template>
    <Head title="Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">Produk</h2>
                <div class="flex gap-2">
                    <Link :href="route('products.barcode-print')">
                        <SecondaryButton>Cetak Barcode</SecondaryButton>
                    </Link>
                    <Link :href="route('products.create')">
                        <PrimaryButton>Tambah Produk</PrimaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="card p-4 mb-6 flex flex-wrap items-center gap-4">
                    <TextInput v-model="search" placeholder="Cari nama, SKU, atau barcode..." class="w-72" />
                    <select v-model="categoryFilter" class="rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Semua Kategori</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                    <select v-model="statusFilter" class="rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="card">
                    <div class="table-wrapper overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="py-3 font-medium text-slate-700">Gambar</th>
                                    <th class="py-3 font-medium text-slate-700">SKU</th>
                                    <th class="py-3 font-medium text-slate-700">Nama</th>
                                    <th class="py-3 font-medium text-slate-700">Kategori</th>
                                    <th class="py-3 font-medium text-slate-700">Harga Jual</th>
                                    <th class="py-3 font-medium text-slate-700">Stok</th>
                                    <th class="py-3 font-medium text-slate-700">Status</th>
                                    <th class="py-3 font-medium text-slate-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data ?? []" :key="product.id" class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="py-3">
                                        <img v-if="product.image" :src="`${$page.props.app.storage_url}/${product.image}`" class="h-10 w-10 rounded object-cover" />
                                        <div v-else class="flex h-10 w-10 items-center justify-center rounded bg-slate-100 text-xs text-slate-400">No img</div>
                                    </td>
                                    <td class="py-3 font-mono text-xs">{{ product.sku }}</td>
                                    <td class="py-3 font-medium">{{ product.name }}</td>
                                    <td class="py-3">{{ product.category?.name }}</td>
                                    <td class="py-3">Rp {{ Number(product.sell_price).toLocaleString('id-ID') }}</td>
                                    <td class="py-3">
                                        <span :class="product.stock <= product.minimum_stock ? 'text-red-600 font-semibold' : ''">
                                            {{ product.stock }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <button @click="toggleStatus(product)" class="rounded-full px-2 py-0.5 text-xs font-medium" :class="product.status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                            {{ product.status ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </td>
                                    <td class="py-3">
                                        <div class="flex gap-2">
                                            <Link :href="route('products.edit', product.id)">
                                                <SecondaryButton>Edit</SecondaryButton>
                                            </Link>
                                            <DangerButton @click="confirmDelete = product">Hapus</DangerButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="(products.data ?? []).length === 0">
                                    <td colspan="8" class="py-6 text-center text-slate-500">Tidak ada produk</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="products.links" class="flex justify-center gap-1 p-4">
                        <component :is="'a'" v-for="(link, i) in products.links" :key="i"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="rounded-md px-3 py-1 text-sm"
                            :class="link.active ? 'bg-primary-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-100'"
                        />
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="confirmDelete !== null" @close="confirmDelete = null">
            <div class="p-6">
                <h3 class="text-lg font-medium text-slate-900">Hapus Produk</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Apakah Anda yakin ingin menghapus <strong>{{ confirmDelete?.name }}</strong>?
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="confirmDelete = null">Batal</SecondaryButton>
                    <DangerButton @click="destroy(confirmDelete.id)" :disabled="deleteForm.processing">Hapus</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
