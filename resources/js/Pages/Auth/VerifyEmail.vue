<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Verify your email" />

    <div class="min-h-screen bg-neutral-100">
        <!-- top bar -->
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-6">
                <Link href="/" class="text-xl font-bold">BookApp</Link>
                <div class="space-x-4">
                    <Link :href="route('login')" class="text-sm font-medium">Log in</Link>
                </div>
            </div>
        </div>

        <!-- backdrop + centered card (same look as login/register) -->
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl p-6">
                <div class="text-center mb-4">
                    <h1 class="text-2xl font-semibold">Verify your email</h1>
                </div>

                <p class="text-sm text-neutral-700">
                    We’ve sent a verification link to your email address. Please click the link to verify.
                    If you didn’t receive the email, you can request another one.
                </p>

                <div class="mt-6 space-y-3">
                    <!-- resend -->
                    <Link :href="route('verification.send')" method="post" as="button"
                          class="w-full rounded-full bg-black text-white px-4 py-3 text-sm font-medium hover:bg-neutral-800">
                        Resend verification email
                    </Link>

                    <!-- go to login (even before verifying, user can go login page) -->
                    <Link :href="route('login')"
                          class="w-full rounded-full border border-neutral-300 px-4 py-3 text-sm font-medium text-center hover:bg-neutral-50">
                        Go to Login
                    </Link>
                </div>

                <p class="mt-4 text-xs text-neutral-500 text-center">
                    After verifying, you’ll be redirected to the login screen automatically.
                </p>
            </div>
        </div>
    </div>
</template>
