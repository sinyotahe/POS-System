<script setup>
defineProps({
    message: { type: String, default: '' },
});
</script>

<template>
    <p v-if="message" class="mt-1 text-xs text-red-500">{{ message }}</p>
</template>
