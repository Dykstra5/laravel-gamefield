<script setup>

defineProps({
    selected: Boolean,
    text: String
});
</script>

<template>
    <button :class="[
        'px-3 pt-2 pb-1 max-w-32 mt-1 outline-none border-l-4 border-transparent w-[120px]',
        selected
            ? 'border-rose-500'
            : '',
    ]">
        {{ text }}   
    </button>
</template>
