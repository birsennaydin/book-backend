<script setup>
// Props for v-model and width control
const props = defineProps({
    modelValue: { type: Boolean, default: false }, // for v-model
    maxWidth: { type: String, default: 'max-w-md' }, // Tailwind width control
})

// Emits event to update v-model
const emit = defineEmits(['update:modelValue'])

// Close modal
function close() {
    emit('update:modelValue', false)
}

// Close when clicking on backdrop (outside the modal box)
function onBackdrop(e) {
    if (e.target === e.currentTarget) close()
}
</script>

<template>
    <!-- Teleport modal to body to avoid layout issues -->
    <teleport to="body">
        <!-- Modal wrapper -->
        <div
            v-if="modelValue"
            class="fixed inset-0 z-50"
            @click="onBackdrop"
        >
            <!-- Dark backdrop -->
            <div class="absolute inset-0 bg-black/50"></div>

            <!-- Modal content -->
            <div class="relative mx-auto mt-24 w-full px-4">
                <div :class="['mx-auto rounded-2xl bg-white p-6 shadow-xl', maxWidth]">
                    <!-- Slot for modal content -->
                    <slot />
                </div>
            </div>

            <!-- Close button -->
            <button
                class="absolute right-5 top-5 rounded-full bg-white/90 px-2 py-1 text-sm shadow hover:bg-white"
                @click="close"
                aria-label="Close modal"
            >
                ✕
            </button>
        </div>
    </teleport>
</template>

<style scoped>
/* Tailwind handles most styling — no extra CSS needed by default */
</style>
