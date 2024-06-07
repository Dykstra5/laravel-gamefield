<script setup>
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import TagItem from './TagItem.vue';

const searchKeyword = ref('');

defineProps({
    tagsFollowing: Object,
});

</script>

<template>
    <div class="mt-5 max-h-[200px] md:h-full md:flex-1 overflow-auto">
        <div v-if="tagsFollowing.data.length > 0" class="flex flex-wrap text-gray-600 text-center p-1">
            <TagItem v-for="tag of tagsFollowing.data" :tag="tag" class="col-span-12 md:col-span-6" />
        </div>
        <div v-else>
            No tienes ningún tema añadido a Favoritos
        </div>
    </div>
</template>

<style scoped></style>