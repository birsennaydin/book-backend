<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

function submit() {
    form.post(route('login'), { onFinish: () => form.reset('password') })
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input v-model="form.email" type="email" required
                   class="block w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input v-model="form.password" type="password" required
                   class="block w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm">
                <input v-model="form.remember" type="checkbox" class="rounded border-neutral-300">
                Remember me
            </label>
            <Link :href="route('password.request')" class="text-sm underline">Forgot password?</Link>
        </div>

        <button type="submit" :disabled="form.processing"
                class="w-full rounded-full bg-black text-white px-4 py-3 text-sm font-medium hover:bg-neutral-800 disabled:opacity-60">
            Log in
        </button>
    </form>
</template>
