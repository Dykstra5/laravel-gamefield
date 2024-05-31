<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    placeholder: String,
    autoResize: {
        type: Boolean,
        default: true
    },
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

function adjustHeight() {
    if (props.autoResize) {   
        input.value.style.height = 'auto';
        // Ajusta la altura al scrollHeight
        input.value.style.height = input.value.scrollHeight + 'px';
    }
}
</script>

<template>
    <textarea
        class="border-gray-300 dark:border-gray-700 dark:bg-white dark:text-black focus:border-rose-500 dark:focus:border-rose-600 focus:ring-rose-500 dark:focus:ring-rose-600 rounded-md shadow-sm"
        v-model="model" 
        @input="adjustHeight" 
        ref="input" 
        :placeholder="placeholder"></textarea>
</template>
