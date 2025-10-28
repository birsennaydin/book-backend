<script setup>
import { Head } from '@inertiajs/vue3'
import { router, usePage } from '@inertiajs/vue3'
import { onMounted, watch } from 'vue'
import SiteHeader from '@/Components/Layouts/SiteHeader.vue'
import AuthModal from '@/Components/Auth/AuthModal.vue'
import { useAuthModal } from '@/Composables/useAuthModal'

const { isOpen, mode, open, close } = useAuthModal()

// Read ?auth=... and open modal accordingly
function syncModalFromQuery() {
    const url = new URL(window.location.href)
    const m = url.searchParams.get('auth')
    if (m === 'login' || m === 'register') open(m)
}

// Remove ?auth when closing the modal (keep background page)
function clearAuthQuery() {
    router.visit(route('home'), {
        replace: true,
        preserveScroll: true,
        preserveState: true,
    })
}

// Close hook: call clearAuthQuery after closing
const _origClose = close
function modalClose() {
    _origClose()
    clearAuthQuery()
}

// Run on mount + whenever Inertia URL changes
onMounted(syncModalFromQuery)
watch(() => usePage().url, syncModalFromQuery)

</script>

<template>
    <Head title="BookApp — Read. Write. Connect." />

    <SiteHeader />

    <!-- Hero -->
    <section class="relative overflow-hidden bg-[#F5E3D6]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <h1 class="text-5xl font-extrabold leading-[1.05] tracking-tight text-neutral-900 sm:text-6xl">
                        Come for the story.<br />
                        <span class="inline-block">Stay for the connection.</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-lg text-neutral-700">
                        Discover fresh stories, build your audience, and chat with a community that cares about your words.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <button @click="open('register')"
                                class="inline-flex items-center justify-center rounded-full bg-black px-6 py-3 text-sm font-semibold text-white hover:bg-neutral-800">
                            Get started
                        </button>
                        <button @click="open('login')"
                                class="inline-flex items-center justify-center rounded-full border border-neutral-300 bg-white px-6 py-3 text-sm font-semibold text-neutral-900 hover:bg-neutral-50">
                            Already have an account? Log in
                        </button>
                    </div>
                </div>

                <!-- Right: Illustration -->
                <div class="relative">
                    <div class="mx-auto h-[520px] w-[360px] rounded-[180px] bg-[#F6A385]/50 blur-[1px]"></div>

                    <!-- Floating cards -->
                    <div class="pointer-events-none">
                        <div class="absolute left-1/2 top-16 w-72 -translate-x-1/2 rounded-2xl bg-white p-4 shadow-xl ring-1 ring-black/5">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 shrink-0 rounded-full bg-amber-200"></div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold">@romantasygirl3</p>
                                    <p class="mt-1 line-clamp-2 text-sm text-neutral-700">
                                        from banter to BREAKUP in two paragraphs? 🤯
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute right-3 top-56 w-72 rounded-2xl bg-white p-4 shadow-xl ring-1 ring-black/5">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 shrink-0 rounded-full bg-fuchsia-200"></div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold">@deleted_chapter</p>
                                    <p class="mt-1 line-clamp-2 text-sm text-neutral-700">
                                        What in the BookApp?! (i love it)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute left-6 bottom-10 w-64 rounded-2xl bg-white p-4 shadow-xl ring-1 ring-black/5">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 shrink-0 rounded-full bg-sky-200"></div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold">@first_draft</p>
                                    <p class="mt-1 line-clamp-2 text-sm text-neutral-700">
                                        Chapter 12 is LIVE. Feedback welcome!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <AuthModal v-model="isOpen" :mode="mode" @update:modelValue="val => { if(!val) modalClose() }" />

</template>
