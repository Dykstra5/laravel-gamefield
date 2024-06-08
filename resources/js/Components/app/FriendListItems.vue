<script setup>
import { ref } from 'vue';
import UserItem from '@/Components/app/UserItem.vue';

const searchKeyword = ref('');

defineProps({
    usersFollowing: Object,
});

</script>

<template>
    <div class="mt-5 max-h-[200px] md:max-h-full md:h-full md:flex-1 overflow-auto">
        <div v-if="usersFollowing.data.length > 0" class="text-gray-600 text-center p-1">
            <UserItem v-for="user of usersFollowing.data" :user="user" :isMyProfile="false" class="col-span-12 md:col-span-6" />
        </div>
        <div v-else>
            No sigues a nadie
        </div>
    </div>
</template>

<style scoped></style>