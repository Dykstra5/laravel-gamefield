<script setup>
const props = defineProps({
    value: {
        type: String
    },
    class: String
});
</script>

<template>
    <label class="block font-medium text-sm " :class="[
        props.class ? props.class : 'text-gray-700 dark:text-gray-300',
    ]">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
