<script setup>

defineProps({
    selected: Boolean,
    text: String
});
</script>

<template>
    <button :class="[
        'px-3 pt-2 pb-1 max-w-32 mt-1 mx-1 outline-none rounded-t-lg ',
        selected
            ? 'bg-gradient-to-tr from-rose-900 to-red-500 text-white shadow border-b-[6px] border-red-600 transition-all'
            : 'text-gray-500 mt-2 hover:text-black hover:border-b-4 hover:pb-0 border-red-500',
    ]">
        {{ text }}   
    </button>
</template>
