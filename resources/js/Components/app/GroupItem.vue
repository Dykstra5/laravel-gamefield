<script setup>
defineProps({
    image: String,
    title: String,
    description: String
})
</script>

<template>
    <div class="mb-3 hover:bg-gray-100 cursor-pointer">
        <div class="flex items-start gap-3 py-2 px-2">
            <img :src="image" class="w-[32px] rounded-full" alt="random">
            <div>
                <h3 class="font-black text-md">{{ title }}</h3>
                <div class="text-sm text-gray-500">{{ description }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>