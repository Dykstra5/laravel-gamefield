<script setup>
import FriendItem from'@/Components/app/FriendItem.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const searchKeyword = ref('');

</script>

<template>
    <TextInput :model="searchKeyword" placeholder="Buscar..." class=" w-full mt-4" />
    <div class="mt-5 h-[200px] md:h-full md:flex-1 overflow-auto">
        <div v-if="false" class="text-gray-600 text-center p-5">
            No tienes amigos
        </div>
        <div v-else>
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
            <FriendItem image="https://picsum.photos/100" title="John Doe" />
            <FriendItem image="https://picsum.photos/100" title="Elon Musk" />
        </div>
    </div>
</template>

<style scoped></style>