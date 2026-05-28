<script setup>
defineProps({
    type: { type: String, default: 'gray' },
})

const classes = {
    blue: 'badge-blue',
    green: 'badge-green',
    red: 'badge-red',
    yellow: 'badge-yellow',
    gray: 'badge-gray',
}
</script>

<template>
    <span :class="classes[type] || classes.gray">
        <slot />
    </span>
</template>
