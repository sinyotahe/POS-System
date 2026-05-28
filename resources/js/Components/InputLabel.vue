<script setup>
defineProps({
    value: { type: String, required: true },
});
</script>

<template>
    <label class="block text-sm font-medium text-slate-700">
        {{ value }}
    </label>
</template>
