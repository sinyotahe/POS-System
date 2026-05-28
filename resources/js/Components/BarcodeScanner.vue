<script setup>
import { ref, watch, onUnmounted } from 'vue'

const emit = defineEmits(['scan', 'close'])
const props = defineProps({
    open: { type: Boolean, default: false },
})

const error = ref('')
let scanner = null
let stopping = false

async function startScanner() {
    error.value = ''
    try {
        const { Html5Qrcode } = await import('html5-qrcode')
        scanner = new Html5Qrcode('barcode-reader')
        await scanner.start(
            { facingMode: 'environment' },
            { fps: 15, qrbox: { width: 250, height: 150 } },
            async (decodedText) => {
                emit('scan', decodedText)
                await stopScanner()
                emit('close')
            },
            () => {},
        )
    } catch (e) {
        if (e.name === 'NotAllowedError') {
            error.value = 'Izin kamera ditolak. Izinkan akses kamera di pengaturan browser.'
        } else {
            error.value = 'Gagal mengakses kamera: ' + (e.message || '')
        }
    }
}

async function stopScanner() {
    if (!scanner || stopping) return
    stopping = true
    try {
        await scanner.stop()
        await scanner.clear()
    } catch {} finally {
        scanner = null
        stopping = false
    }
}

watch(() => props.open, (val) => {
    if (val) {
        startScanner()
    } else {
        stopScanner()
        error.value = ''
    }
})

onUnmounted(() => { stopScanner() })
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" @click.self="emit('close')">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow-xl">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Scan Barcode</h3>
                <button @click="emit('close')" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="barcode-reader" class="w-full overflow-hidden rounded-lg" style="min-height: 200px;"></div>
            <p v-if="error" class="mt-2 text-center text-sm text-red-500">{{ error }}</p>
            <p v-else class="mt-2 text-center text-xs text-gray-400">Arahkan kamera ke barcode produk</p>
        </div>
    </div>
</template>
