<script setup>

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    options: {
        type: Object,
        required: true,
    },
    id: {
        type: String,
        required: true,
    },
});

</script>

<template>
    <select v-model="model" :name="props.id" :id="props.id" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-white dark:text-black focus:border-rose-500 dark:focus:border-rose-600 focus:ring-rose-500 dark:focus:ring-rose-600">
        <option v-for="option of options" :value="option.name">{{ option.name }}</option>
    </select>
</template>
