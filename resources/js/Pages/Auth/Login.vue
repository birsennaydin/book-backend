<script setup>
import { Head, Link } from '@inertiajs/vue3'
import SocialButton from '@/Components/Auth/SocialButton.vue'
import EmailLoginForm from '@/Components/Auth/EmailLoginForm.vue'

const googleUrl = '/auth/google/redirect'
const facebookUrl = '/auth/facebook/redirect'
</script>

<template>
    <Head title="Log in" />
    <div class="min-h-screen bg-neutral-100">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-6">
                <Link href="/" class="text-xl font-bold">BookApp</Link>
                <div class="space-x-4">
                    <Link :href="`${route('home')}?auth=register`" class="text-sm font-medium">Sign up</Link>
                </div>
            </div>
        </div>

        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl p-6">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-semibold">Log in to BookApp</h1>
                </div>

                <div class="space-y-3">
                    <SocialButton :href="googleUrl" provider="google" label="Log in with Google">
                        <template #icon>
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="h-5 w-5" alt="">
                        </template>
                    </SocialButton>

                    <SocialButton :href="facebookUrl" provider="facebook" label="Log in with Facebook">
                        <template #icon>
                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-[#1877F2]"><path d="M22 12.07C22 6.49 17.52 2 11.93 2S2 6.49 2 12.07c0 5.03 3.66 9.2 8.44 9.93v-7.03H7.9v-2.9h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.62.77-1.62 1.56v1.87h2.77l-.44 2.9h-2.33V22c4.78-.73 8.44-4.9 8.44-9.93Z"/></svg>
                        </template>
                    </SocialButton>

                    <div class="flex items-center gap-3 my-4">
                        <div class="h-px bg-neutral-200 flex-1"></div>
                        <span class="text-xs text-neutral-500">or</span>
                        <div class="h-px bg-neutral-200 flex-1"></div>
                    </div>

                    <EmailLoginForm />

                    <div class="text-center text-sm mt-3">
                        Don’t have an account?
                        <Link :href="`${route('home')}?auth=register`" class="font-semibold underline">Sign up</Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
