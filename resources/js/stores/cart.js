import { defineStore } from 'pinia'
import { usePage } from '@inertiajs/vue3'

const taxRate = () => usePage().props.app?.tax_rate ?? 0.11

const STORAGE_KEY = 'pos_cart'

function loadPersistedCart() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY)
        if (raw) return JSON.parse(raw)
    } catch {}
    return null
}

export const useCartStore = defineStore('cart', {
    state: () => {
        const persisted = loadPersistedCart()
        return {
            items: persisted?.items ?? [],
            discount: persisted?.discount ?? 0,
            customer_name: persisted?.customer_name ?? '',
            payment_method: persisted?.payment_method ?? 'cash',
            paid_amount: persisted?.paid_amount ?? 0,
        }
    },

    getters: {
        subtotal(state) {
            return state.items.reduce((sum, item) => {
                const itemTotal = item.price * item.qty
                const itemDisc = Math.round(itemTotal * (item.item_discount || 0) / 100 * 100) / 100
                return sum + itemTotal - itemDisc
            }, 0)
        },

        tax(state) {
            return Math.round(this.subtotal * taxRate() * 100) / 100
        },

        discountAmount(state) {
            return Math.round(this.subtotal * this.discount / 100 * 100) / 100
        },

        grandTotal(state) {
            const rate = taxRate()
            const total = this.subtotal + Math.round(this.subtotal * rate * 100) / 100
            return Math.max(0, total - this.discountAmount)
        },

        taxRatePercent(state) {
            return Math.round(taxRate() * 10000) / 100
        },

        itemCount(state) {
            return state.items.reduce((sum, item) => sum + item.qty, 0)
        },

        change(state) {
            return Math.max(0, this.paid_amount - this.grandTotal)
        },
    },

    actions: {
        addItem(product) {
            const existing = this.items.find((i) => i.product_id === product.id)
            if (existing) {
                existing.qty++
            } else {
                this.items.push({
                    product_id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: Number(product.sell_price),
                    qty: 1,
                    stock: Number(product.stock),
                    item_discount: 0,
                })
            }
        },

        removeItem(productId) {
            this.items = this.items.filter((i) => i.product_id !== productId)
        },

        updateQty(productId, qty) {
            const item = this.items.find((i) => i.product_id === productId)
            if (item) {
                qty = Math.max(1, qty)
                item.qty = qty
                if (qty === 0) {
                    this.removeItem(productId)
                }
            }
        },

        updateItemDiscount(productId, percent) {
            const item = this.items.find((i) => i.product_id === productId)
            if (item) {
                item.item_discount = Math.min(100, Math.max(0, Number(percent) || 0))
            }
        },

        clearCart() {
            this.items = []
            this.discount = 0
            this.customer_name = ''
            this.payment_method = 'cash'
            this.paid_amount = 0
            localStorage.removeItem(STORAGE_KEY)
        },

        setDiscount(percent) {
            this.discount = Math.min(100, Math.max(0, Number(percent) || 0))
        },

        persist() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                items: this.items,
                discount: this.discount,
                customer_name: this.customer_name,
                payment_method: this.payment_method,
                paid_amount: this.paid_amount,
            }))
        },
    },
})
