<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed, onMounted, nextTick } from 'vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
    products: { type: Array, required: true },
})

const search = ref('')
const selectedIds = ref(new Set())
const printMode = ref(false)

const filteredProducts = computed(() => {
    if (!search.value) return props.products
    const q = search.value.toLowerCase()
    return props.products.filter(
        (p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q) || (p.barcode && p.barcode.toLowerCase().includes(q)),
    )
})

const selectedProducts = computed(() =>
    props.products.filter((p) => selectedIds.value.has(p.id)),
)

function toggleAll() {
    if (selectedIds.value.size === filteredProducts.value.length) {
        selectedIds.value = new Set()
    } else {
        selectedIds.value = new Set(filteredProducts.value.map((p) => p.id))
    }
}

function toggle(id) {
    const s = new Set(selectedIds.value)
    if (s.has(id)) s.delete(id)
    else s.add(id)
    selectedIds.value = s
}

function generateBarcode(svgEl, code) {
    if (!svgEl || !code) return
    import('jsbarcode').then(({ default: JsBarcode }) => {
        JsBarcode(svgEl, code, {
            format: 'CODE128',
            width: 1.5,
            height: 40,
            displayValue: false,
            margin: 0,
            background: 'transparent',
            fontSize: 10,
        })
    })
}

async function doPrint() {
    document.querySelectorAll('.barcode-svg').forEach((el) => {
        const code = el.dataset.code
        if (code) generateBarcode(el, code)
    })
    await new Promise((r) => setTimeout(r, 500))
    printMode.value = true
    await nextTick()
    window.print()
}

onMounted(() => {
    selectedIds.value = new Set(props.products.map((p) => p.id))
})
</script>

<template>
    <Head title="Cetak Barcode" />

    <AuthenticatedLayout>
        <div v-if="!printMode">
            <div class="page-header">
                <h2 class="text-2xl font-bold text-slate-900">Cetak Barcode</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih produk untuk dicetak label barcode</p>
            </div>

            <div class="card p-4 mb-6">
                <div class="flex items-center gap-3">
                    <TextInput v-model="search" type="text" class="block max-w-xs" placeholder="Cari produk..." />
                    <span class="text-sm text-slate-500">{{ filteredProducts.length }} produk</span>
                </div>
            </div>

            <div class="card overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="w-10 px-4 py-3 text-left">
                                <input type="checkbox" :checked="selectedIds.size === filteredProducts.length && filteredProducts.length > 0" @change="toggleAll" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500" />
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">SKU</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Barcode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in filteredProducts" :key="product.id" class="border-b border-slate-100 hover:bg-slate-50 cursor-pointer" @click="toggle(product.id)">
                            <td class="px-4 py-3" @click.stop>
                                <input type="checkbox" :checked="selectedIds.has(product.id)" @change="toggle(product.id)" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500" />
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ product.name }}</td>
                            <td class="px-4 py-3 text-sm font-mono text-slate-500">{{ product.sku }}</td>
                            <td class="px-4 py-3 text-sm font-mono text-slate-500">{{ product.barcode }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-slate-800">Rp {{ Number(product.sell_price).toLocaleString('id-ID') }}</td>
                        </tr>
                        <tr v-if="filteredProducts.length === 0">
                            <td colspan="5" class="py-12 text-center text-sm text-slate-400">Produk tidak ditemukan</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <Link :href="route('products.index')" class="btn-secondary">Kembali</Link>
                <button @click="doPrint" :disabled="selectedIds.size === 0" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak ({{ selectedIds.size }})
                </button>
            </div>
        </div>

        <!-- Print Layout -->
        <div v-if="printMode" class="no-print">
            <div class="page-header">
                <h2 class="text-2xl font-bold text-slate-900">Cetak Barcode</h2>
            </div>
            <button @click="printMode = false" class="btn-secondary mb-4">Kembali</button>
        </div>

        <div v-show="printMode" class="print-only">
            <div class="barcode-grid">
                <div v-for="product in selectedProducts" :key="product.id" class="barcode-label">
                    <svg :data-code="product.barcode" class="barcode-svg" ref="barcodeRefs"></svg>
                    <div class="barcode-info">
                        <span class="barcode-sku">{{ product.sku }}</span>
                        <span class="barcode-name">{{ product.name }}</span>
                        <span class="barcode-price">Rp {{ Number(product.sell_price).toLocaleString('id-ID') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@media print {
    .no-print { display: none !important; }
    body { margin: 0; padding: 8px; }
    @page { margin: 8mm; }

    .print-only {
        display: block !important;
    }

    .barcode-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
    }

    .barcode-label {
        width: 48%;
        border: 1px dashed #ddd;
        padding: 6px;
        text-align: center;
        page-break-inside: avoid;
        box-sizing: border-box;
    }

    .barcode-svg {
        max-width: 100%;
        height: auto;
    }

    .barcode-info {
        margin-top: 2px;
        font-size: 9px;
        line-height: 1.2;
    }

    .barcode-sku {
        display: block;
        font-family: monospace;
        font-weight: 600;
        color: #1e293b;
    }

    .barcode-name {
        display: block;
        color: #475569;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .barcode-price {
        display: block;
        font-weight: 700;
        color: #2563eb;
    }
}
</style>
