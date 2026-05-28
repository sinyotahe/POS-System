import { ref, watch } from 'vue'

const isDark = ref(false)

function initDarkMode() {
    const stored = localStorage.getItem('dark-mode')
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    isDark.value = stored !== null ? stored === 'true' : prefersDark
    applyTheme(isDark.value)
}

function applyTheme(val) {
    document.documentElement.classList.toggle('dark', val)
    const meta = document.querySelector('meta[name="theme-color"]')
    if (meta) meta.content = val ? '#0f172a' : '#2563eb'
}

watch(isDark, (val) => {
    localStorage.setItem('dark-mode', val)
    applyTheme(val)
})

export function useDarkMode() {
    return {
        isDark,
        toggle: () => isDark.value = !isDark.value,
    }
}

export { initDarkMode }
