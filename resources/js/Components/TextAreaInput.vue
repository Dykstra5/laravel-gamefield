<script setup>
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        required: false,
    },
    placeholder: String,
    autoResize: {
        type: Boolean,
        default: true
    },
});

const emit = defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

watch(() => props.modelValue, () => {
    setTimeout(() => {
        adjustHeight();
    }, 1);
});

function adjustHeight() {
    if (props.autoResize) {   
        input.value.style.height = 'auto';
        // Ajusta la altura al scrollHeight
        input.value.style.height = (input.value.scrollHeight) + 2 + 'px';
    }
}

function inputChange(e) {
    emit('update:modelValue', e.target.value);
}
</script>

<template>
    <textarea
        class="border-gray-300 dark:border-gray-700 dark:bg-white dark:text-black focus:border-rose-500 dark:focus:border-rose-600 focus:ring-rose-500 dark:focus:ring-rose-600 rounded-md shadow-sm"
        :value="modelValue" 
        @input="inputChange" 
        ref="input" 
        :placeholder="placeholder"></textarea>
</template>
