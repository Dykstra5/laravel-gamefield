<script setup>
import { TagIcon } from '@heroicons/vue/20/solid';


defineProps({
    tag: Object
})
</script>

<template>
    <a :href="route('tag', { type: tag.type, slug: tag.slug })"
        class="flex items-center justify-center rounded-md w-fit px-1 py-1 m-1 text-xs text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
        :class="[
            tag.type === 'game' ? 'bg-rose-600 hover:bg-rose-500' : '',
            tag.type === 'genre' ? 'bg-red-700 hover:bg-red-600' : '',
            tag.type === 'platform' ? 'bg-pink-700 hover:bg-pink-600' : '',
            tag.type === 'developer' ? 'bg-fuchsia-700 hover:bg-fuchsia-600' : ''
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