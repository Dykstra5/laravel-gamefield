<script setup>

defineProps({
    selected: Boolean,
    text: String
});
</script>

<template>
    <button :class="[
        'px-3 pt-2 pb-1 max-w-32 mt-1 outline-none border-l-4 w-[120px] text-left',
        selected
            ? 'border-rose-500 pl-5'
            : 'border-transparent',
    ]">
        {{ text }}   
    </button>
</template>
