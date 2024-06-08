<script setup>
import { TagIcon } from '@heroicons/vue/20/solid';


defineProps({
    tag: Object
})
</script>

<template>
    <a :href="route('tag', { type: tag.type, slug: tag.slug })"
        class="flex items-center justify-center rounded-md w-fit px-1 py-1 my-1 text-xs text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
        :class="[
            tag.type === 'game' ? 'bg-rose-600 hover:bg-rose-500' : '',
            tag.type === 'genre' ? 'bg-emerald-700 hover:bg-emerald-600' : '',
            tag.type === 'platform' ? 'bg-blue-700 hover:bg-blue-600' : '',
            tag.type === 'developer' ? 'bg-yellow-700 hover:bg-yellow-600' : ''
        ]">
        <div class=" min-w-3 min-h-3">
            <TagIcon class="size-3 mr-1" />
        </div>
        <div class="h-full flex items-center">
            <p class="flex self-center justify-center w-fit" style="line-height: normal">{{ tag.name }}</p>
        </div>
    </a>
</template>

<style scoped></style>