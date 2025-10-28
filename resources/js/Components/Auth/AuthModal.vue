<script setup>
import Modal from '@/Components/ui/Modal.vue'
import SocialButton from '@/Components/Auth/SocialButton.vue'
import EmailLoginForm from '@/Components/Auth/EmailLoginForm.vue'
import EmailRegisterForm from '@/Components/Auth/EmailRegisterForm.vue'
import { ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    modelValue: { type: Boolean, default: false },
    mode: { type: String, default: 'choose' }, // 'choose' | 'login' | 'register'
})
const emit = defineEmits(['update:modelValue'])

const open = ref(props.modelValue)
watch(() => props.modelValue, v => (open.value = v))
watch(open, v => emit('update:modelValue', v))

const view = ref(props.mode)

const googleUrl = '/auth/google/redirect'
const facebookUrl = '/auth/facebook/redirect'
</script>

<template>
    <Modal v-model="open" :maxWidth="'max-w-md'">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-semibold" v-if="view==='login'">Log in to BookApp</h1>
            <h1 class="text-2xl font-semibold" v-else-if="view==='register'">Sign up to join</h1>
            <h1 class="text-2xl font-semibold" v-else>Welcome</h1>
        </div>

        <!-- CHOOSE -->
        <div v-if="view==='choose'" class="space-y-3">
            <SocialButton :href="googleUrl" provider="google" label="Continue with Google">
                <template #icon>
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="h-5 w-5" alt="">
                </template>
            </SocialButton>

            <SocialButton :href="facebookUrl" provider="facebook" label="Continue with Facebook">
                <template #icon>
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-[#1877F2]"><path d="M22 12.07C22 6.49 17.52 2 11.93 2S2 6.49 2 12.07c0 5.03 3.66 9.2 8.44 9.93v-7.03H7.9v-2.9h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.62.77-1.62 1.56v1.87h2.77l-.44 2.9h-2.33V22c4.78-.73 8.44-4.9 8.44-9.93Z"/></svg>
                </template>
            </SocialButton>

            <div class="flex items-center gap-3 my-4">
                <div class="h-px bg-neutral-200 flex-1"></div>
                <span class="text-xs text-neutral-500">or</span>
                <div class="h-px bg-neutral-200 flex-1"></div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button @click="view='login'" class="rounded-full border border-neutral-300 px-4 py-3 text-sm font-medium hover:bg-neutral-50">
                    Log in with email
                </button>
                <button @click="view='register'" class="rounded-full bg-black text-white px-4 py-3 text-sm font-medium hover:bg-neutral-800">
                    Sign up with email
                </button>
            </div>
        </div>

        <!-- EMAIL LOGIN -->
        <div v-else-if="view==='login'" class="space-y-4">
            <button type="button" class="text-sm text-neutral-600 hover:underline" @click="view='choose'">← Back to all login options</button>
            <EmailLoginForm />
            <div class="text-center text-sm mt-3">
                Don’t have an account?
                <Link href="/register" class="font-semibold underline">Sign up</Link>
            </div>
        </div>

        <!-- EMAIL REGISTER -->
        <div v-else-if="view==='register'" class="space-y-4">
            <button type="button" class="text-sm text-neutral-600 hover:underline" @click="view='choose'">← Back to all signup options</button>
            <EmailRegisterForm />
            <div class="text-center text-sm mt-3">
                Already have an account?
                <Link href="/login" class="font-semibold underline">Log in</Link>
            </div>
        </div>
    </Modal>
</template>
