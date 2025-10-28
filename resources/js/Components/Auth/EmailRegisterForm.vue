<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    marketing_opt_in: false,
})

function submit() {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">E-mail</label>
            <input v-model="form.email" type="email" required
                   class="block w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Username</label>
            <input v-model="form.username" type="text" required
                   class="block w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
            <p v-if="form.errors.username" class="mt-1 text-sm text-red-600">{{ form.errors.username }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input v-model="form.password" type="password" required
                   class="block w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Re-enter Password</label>
            <input v-model="form.password_confirmation" type="password" required
                   class="block w-full rounded-lg border border-neutral-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
        </div>

        <label class="flex items-start gap-2 text-sm">
            <input v-model="form.marketing_opt_in" type="checkbox" class="mt-1 rounded border-neutral-300">
            <span>Yes, I’d like to receive marketing emails (optional)</span>
        </label>

        <button type="submit" :disabled="form.processing"
                class="w-full rounded-full bg-black text-white px-4 py-3 text-sm font-medium hover:bg-neutral-800 disabled:opacity-60">
            Create account
        </button>
    </form>
</template>
