import { ref } from 'vue'

const isOpen = ref(false)
const mode = ref('choose') // 'choose' | 'login' | 'register'

export function useAuthModal() {
    function open(m = 'choose') {
        mode.value = m
        isOpen.value = true
    }
    function close() { isOpen.value = false }
    return { isOpen, mode, open, close }
}
