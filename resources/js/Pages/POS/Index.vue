<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useCartStore } from '@/stores/cart'
import BarcodeScanner from '@/Components/BarcodeScanner.vue'
import axios from 'axios'

const props = defineProps({
    products: { type: Array, required: true },
})

const cart = useCartStore()

const search = ref('')
const searchInput = ref(null)
const heldCarts = ref([])
const showHeld = ref(false)
const heldCartsLoading = ref(false)
const showScanner = ref(false)
const scanToast = ref({ show: false, message: '', type: 'success' })
let toastTimer = null

function showScanToast(message, type = 'success') {
    if (toastTimer) clearTimeout(toastTimer)
    scanToast.value = { show: true, message, type }
    toastTimer = setTimeout(() => { scanToast.value = { show: false, message: '', type: 'success' } }, 2500)
}

function playBeep() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)()
        const osc = ctx.createOscillator()
        const gain = ctx.createGain()
        osc.connect(gain)
        gain.connect(ctx.destination)
        osc.frequency.value = 880
        osc.type = 'sine'
        gain.gain.setValueAtTime(0.3, ctx.currentTime)
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15)
        osc.start()
        osc.stop(ctx.currentTime + 0.15)
    } catch {}
}

const handleBarcodeScan = (code) => {
    const product = props.products.find((p) => p.barcode === code || p.sku === code)
    if (product) {
        cart.addItem(product)
        playBeep()
        showScanToast('✓ ' + product.name + ' ditambahkan', 'success')
    } else {
        showScanToast('✗ Barcode ' + code + ' tidak ditemukan', 'error')
    }
}

const handleKeydown = (e) => {
    if (e.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
        e.preventDefault()
        searchInput.value?.focus()
    }
    if (e.key === 'F1') { e.preventDefault(); searchInput.value?.focus() }
    if (e.key === 'F2') { e.preventDefault(); if (cart.items.length > 0) holdTransaction() }
    if (e.key === 'Escape' && search.value) { search.value = ''; searchInput.value?.focus() }
    if ((e.key === 'Enter' && e.ctrlKey) || e.key === 'F8') { e.preventDefault(); if (cart.items.length > 0 && !cartForm.processing) processPayment() }
}

onMounted(() => {
    loadHeldCarts()
    window.addEventListener('keydown', handleKeydown)
    cart.$subscribe(() => cart.persist())
})

onUnmounted(() => window.removeEventListener('keydown', handleKeydown))

const filteredProducts = computed(() => {
    if (!search.value) return props.products
    const q = search.value.toLowerCase()
    return props.products.filter(
        (p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q) || (p.barcode && p.barcode.toLowerCase().includes(q)),
    )
})

watch(search, (val) => {
    if (val.length > 0 && props.products.some((p) => p.barcode && p.barcode === val)) {
        const found = props.products.find((p) => p.barcode === val)
        if (found) { cart.addItem(found); search.value = '' }
    }
})

const cartForm = useForm({
    items: [], discount: 0, tax: 0, customer_name: '', payment_method: 'cash', paid_amount: 0, grand_total: 0,
})

const processPayment = () => {
    if (cart.items.length === 0) return
    if (cart.payment_method === 'cash' && cart.paid_amount < cart.grandTotal) {
        alert('Jumlah dibayar tunai tidak boleh kurang dari grand total (Rp ' + formatPrice(cart.grandTotal) + ').')
        return
    }
    cartForm.discount = cart.discountAmount
    cartForm.tax = cart.tax
    cartForm.customer_name = cart.customer_name
    cartForm.payment_method = cart.payment_method
    cartForm.paid_amount = cart.paid_amount
    cartForm.grand_total = cart.grandTotal
    cartForm.items = cart.items.map((i) => ({
        product_id: i.product_id, qty: i.qty, price: i.price,
        item_discount: i.item_discount || 0,
        subtotal: i.price * i.qty * (1 - (i.item_discount || 0) / 100),
    }))
    cartForm.post(route('pos.store'), {
        preserveScroll: true,
        onSuccess: () => cart.clearCart(),
    })
}

const loadHeldCarts = () => {
    heldCartsLoading.value = true
    axios.get(route('pos.held-carts')).then((res) => { heldCarts.value = res.data }).catch(() => { heldCarts.value = [] }).finally(() => { heldCartsLoading.value = false })
}

const holdTransaction = () => {
    if (cart.items.length === 0) return
    router.post(route('pos.hold'), {
        items: cart.items.map((i) => ({ product_id: i.product_id, qty: i.qty, price: i.price, item_discount: i.item_discount || 0 })),
        discount: cart.discount, customer_name: cart.customer_name, payment_method: cart.payment_method, paid_amount: cart.paid_amount,
    }, {
        preserveScroll: true,
        onSuccess: () => { cart.clearCart(); loadHeldCarts() },
        onError: () => alert('Gagal menahan transaksi'),
    })
}

const resumeHeld = (held) => {
    cart.clearCart()
    cart.discount = Number(held.discount)
    cart.customer_name = held.customer_name || ''
    cart.payment_method = held.payment_method || 'cash'
    cart.paid_amount = Number(held.paid_amount || 0)
    let skipped = 0
    held.items.forEach((i) => {
        const product = props.products.find((p) => p.id === i.product_id)
        if (!product) { skipped++; return }
        cart.addItem(product, i.qty)
        cart.updateQty(product.id, i.qty)
        if (i.item_discount) cart.updateItemDiscount(product.id, i.item_discount)
    })
    if (skipped > 0) alert(`${skipped} produk tidak ditemukan karena sudah dihapus.`)
    router.delete(route('pos.unhold', held.id), {
        preserveScroll: true,
        onSuccess: () => loadHeldCarts(),
        onError: () => alert('Gagal melanjutkan transaksi'),
    })
}

const clearCart = () => cart.clearCart()

const formatPrice = (val) => `Rp ${Number(val).toLocaleString('id-ID')}`

const payAmountOptions = computed(() => {
    const total = cart.grandTotal
    const rounded = Math.ceil(total / 1000) * 1000
    return [rounded, rounded + 5000, rounded + 10000, rounded + 20000, rounded + 50000].filter((v) => v >= total).slice(0, 3)
})
</script>

<template>
    <Head title="POS" />

    <AuthenticatedLayout>
        <!-- Scan Toast -->
        <Transition name="toast">
            <div v-if="scanToast.show"
                class="fixed right-4 top-24 z-[60] flex items-center gap-2 rounded-lg px-4 py-3 shadow-lg"
                :class="scanToast.type === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white'"
            >
                <span class="text-sm font-medium">{{ scanToast.message }}</span>
            </div>
        </Transition>

        <div class="page-header">
            <h2 class="text-2xl font-bold text-slate-900">POS Terminal</h2>
            <p class="mt-1 text-sm text-slate-500">Transaksi penjualan cepat</p>
        </div>

        <div class="flex flex-col gap-6 lg:flex-row">
            <!-- Left - Products -->
            <div class="flex-1">
                <div class="mb-4 flex gap-2">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <TextInput ref="searchInput" v-model="search" class="block w-full pl-10" placeholder="Cari produk... (ketik / atau F1)" />
                    </div>
                    <button @click="showScanner = true" class="flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-600 shadow-sm hover:bg-slate-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span class="hidden sm:inline">Scan</span>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-4">
                    <button
                        v-for="product in filteredProducts" :key="product.id"
                        :disabled="product.stock === 0"
                        @click="cart.addItem(product)"
                        class="group rounded-xl border bg-white p-3 text-left shadow-sm transition-all duration-150 hover:border-primary-200 hover:shadow-md active:scale-[0.98]"
                        :class="product.stock === 0 ? 'cursor-not-allowed opacity-50' : ''"
                    >
                        <div v-if="product.image" class="mb-2 overflow-hidden rounded-lg">
                            <img :src="`${$page.props.app.storage_url}/${product.image}`" class="h-20 w-full object-cover transition-transform group-hover:scale-105" />
                        </div>
                        <div v-else class="mb-2 flex h-20 items-center justify-center rounded-lg bg-slate-50 text-xs text-slate-300">Tidak ada gambar</div>
                        <p class="truncate text-sm font-medium text-slate-800">{{ product.name }}</p>
                        <p class="mt-1 text-base font-bold text-primary-600">{{ formatPrice(product.sell_price) }}</p>
                        <p class="mt-1 text-xs" :class="product.stock > 0 ? 'text-slate-400' : 'text-red-500'">
                            Stok: {{ product.stock }}
                        </p>
                    </button>

                    <div v-if="filteredProducts.length === 0" class="col-span-full py-16 text-center text-sm text-slate-400">
                        Produk tidak ditemukan
                    </div>
                </div>
            </div>

            <!-- Right - Cart Panel -->
            <div class="w-full lg:w-[380px] xl:w-[420px]">
                <div class="sticky top-24 rounded-xl border bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="font-semibold text-slate-800">Keranjang <span class="text-sm font-normal text-slate-400">({{ cart.itemCount }} item)</span></h3>
                        <button v-if="cart.items.length > 0" @click="holdTransaction" class="text-xs text-primary-600 hover:text-primary-700">Hold</button>
                    </div>

                    <div class="max-h-[300px] overflow-y-auto px-5 scrollbar-thin lg:max-h-[400px]">
                        <div v-for="item in cart.items" :key="item.product_id" class="flex items-start gap-3 border-b border-slate-100 py-3 last:border-0">
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-sm font-medium text-slate-800">{{ item.name }}</p>
                                <p class="text-xs text-slate-400">{{ formatPrice(item.price) }}</p>
                                <div class="mt-1 flex items-center gap-1">
                                    <button @click="cart.updateQty(item.product_id, item.qty - 1)" class="flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 text-sm text-slate-500 hover:bg-slate-50">-</button>
                                    <span class="flex h-7 w-8 items-center justify-center text-sm font-semibold text-slate-700">{{ item.qty }}</span>
                                    <button @click="cart.updateQty(item.product_id, item.qty + 1)" class="flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 text-sm text-slate-500 hover:bg-slate-50">+</button>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-800">{{ formatPrice(item.price * item.qty * (1 - (item.item_discount || 0) / 100)) }}</p>
                                <input v-model.number="item.item_discount" @change="cart.updateItemDiscount(item.product_id, item.item_discount)" type="number" min="0" max="100" class="mt-1 w-16 rounded border-slate-200 text-right text-xs shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="0%" />
                                <button @click="cart.removeItem(item.product_id)" class="ml-1 text-xs text-red-400 hover:text-red-600">Hapus</button>
                            </div>
                        </div>
                        <div v-if="cart.items.length === 0" class="py-12 text-center text-sm text-slate-400">Belum ada item</div>
                    </div>

                    <!-- Summary -->
                    <div class="border-t border-slate-200 px-5 py-4">
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Subtotal</span>
                                <span class="font-medium text-slate-800">{{ formatPrice(cart.subtotal) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Diskon (%)</span>
                                <input v-model.number="cart.discount" type="number" min="0" max="100" class="w-20 rounded border-slate-200 text-right text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="0" />
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Pajak ({{ cart.taxRatePercent }}%)</span>
                                <span class="font-medium text-slate-800">{{ formatPrice(cart.tax) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-bold">
                                <span class="text-slate-900">Grand Total</span>
                                <span class="text-primary-600">{{ formatPrice(cart.grandTotal) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="border-t border-slate-200 px-5 py-4">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-500">Pelanggan</label>
                                <TextInput v-model="cart.customer_name" type="text" class="mt-1 block w-full" placeholder="Nama (opsional)" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500">Metode</label>
                                <div class="mt-1 grid grid-cols-4 gap-1.5">
                                    <button v-for="m in [{v:'cash',l:'Tunai'},{v:'transfer',l:'Transfer'},{v:'qris',l:'QRIS'},{v:'e-wallet',l:'E-Wallet'}]" :key="m.v"
                                        @click="cart.payment_method = m.v"
                                        class="rounded-lg py-2 text-xs font-medium transition-all"
                                        :class="cart.payment_method === m.v ? 'bg-primary-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    >{{ m.l }}</button>
                                </div>
                            </div>
                            <div v-if="cart.payment_method === 'cash'">
                                <label class="block text-xs font-medium text-slate-500">Jumlah Dibayar</label>
                                <div class="mt-1 flex gap-2">
                                    <TextInput v-model.number="cart.paid_amount" type="number" class="block flex-1" placeholder="0" />
                                </div>
                                <div v-if="cart.paid_amount > 0" class="mt-2 flex flex-wrap gap-1">
                                    <button v-for="amt in payAmountOptions" :key="amt" @click="cart.paid_amount = amt"
                                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-200"
                                    >{{ formatPrice(amt) }}</button>
                                </div>
                                <div v-if="cart.paid_amount >= cart.grandTotal" class="mt-2 text-sm font-semibold text-emerald-600">
                                    Kembali: {{ formatPrice(cart.change) }}
                                </div>
                            </div>
                        </div>
                        <InputError :message="cartForm.errors.paid_amount" />
                    </div>

                    <!-- Held Carts -->
                    <div class="border-t border-slate-200 px-5 py-3">
                        <button @click="showHeld = !showHeld" class="flex w-full items-center justify-between text-sm text-slate-500 hover:text-slate-700">
                            <span>Ditahan ({{ heldCarts.length }})</span>
                            <svg :class="showHeld ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div v-if="showHeld" class="mt-2 max-h-28 space-y-1 overflow-y-auto">
                            <div v-for="held in heldCarts" :key="held.id" class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-sm">
                                <span class="truncate text-slate-600">{{ held.label || 'Cart' }} ({{ held.items.length }} item)</span>
                                <button @click="resumeHeld(held)" class="text-xs font-medium text-primary-600 hover:text-primary-700">Lanjutkan</button>
                            </div>
                            <div v-if="heldCartsLoading" class="py-2 text-center text-xs text-slate-400">Memuat...</div>
                            <div v-else-if="heldCarts.length === 0" class="py-2 text-center text-xs text-slate-400">Kosong</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 border-t border-slate-200 p-5">
                        <button @click="clearCart" :disabled="cart.items.length === 0" class="btn-secondary flex-1">Batal</button>
                        <button @click="processPayment" :disabled="cart.items.length === 0 || cartForm.processing" class="btn-primary flex-1">Bayar</button>
                    </div>
                </div>
            </div>
        </div>

        <BarcodeScanner :open="showScanner" @scan="handleBarcodeScan" @close="showScanner = false" />
    </AuthenticatedLayout>
</template>

<style scoped>
.toast-enter-active { transition: all 0.3s ease-out; }
.toast-leave-active { transition: all 0.2s ease-in; }
.toast-enter-from { opacity: 0; transform: translateX(30px); }
.toast-leave-to { opacity: 0; transform: translateX(30px); }
</style>
