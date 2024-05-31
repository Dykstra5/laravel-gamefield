<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

defineProps({
    placeholder: String,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 dark:border-gray-700 dark:bg-white dark:text-black focus:border-rose-500 dark:focus:border-rose-600 focus:ring-rose-500 dark:focus:ring-rose-600 rounded-md shadow-sm"
        v-model="model"
        ref="input"
        :placeholder="placeholder"
    />
</template>
