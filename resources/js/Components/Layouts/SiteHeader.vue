<script setup>
import { Link } from '@inertiajs/vue3'
import { useAuthModal } from '@/Composables/useAuthModal'

const { open } = useAuthModal()
</script>

<template>
    <header class="w-full">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-5">
                <Link href="/" class="inline-flex items-center gap-2">
                    <span class="inline-block h-8 w-8 rounded-md bg-black"></span>
                    <span class="text-lg font-semibold">BookApp</span>
                </Link>

                <nav class="flex items-center gap-4">
                    <button @click="open('login')" class="text-sm font-medium hover:underline">Log in</button>
                    <button @click="open('register')"
                            class="rounded-full bg-black px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">
                        Sign Up
                    </button>
                </nav>
            </div>
        </div>
    </header>
</template>
