<script setup>

defineProps({
    selected: Boolean,
    text: String
});
</script>

<template>
    <button :class="[
        'px-2 pt-2 pb-1 max-w-32 mt-1 border-l-8 w-[120px] text-left rounded-r-lg',
        selected
            ? 'pl-5 bg-gradient-to-tr from-rose-900 to-red-500 text-white border-red-600 transition-all'
            : 'border-transparent',
    ]">
        {{ text }}   
    </button>
</template>
