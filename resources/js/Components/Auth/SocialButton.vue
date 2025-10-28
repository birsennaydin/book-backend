<script setup>
const props = defineProps({
    provider: { type: String, required: true }, // 'google' | 'facebook' | 'apple' | 'tiktok'
    href: { type: String, required: true },
    label: { type: String, required: true },
})
</script>

<template>
    <a :href="href"
       class="w-full inline-flex items-center justify-center gap-2 rounded-full border border-neutral-300 px-4 py-3 text-sm font-medium hover:bg-neutral-50">
        <slot name="icon" />
        {{ label }}
    </a>
</template>
