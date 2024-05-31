<script setup>
import PostItem from '@/Components/app/PostItem.vue';

defineProps({
    posts: Array,
})

const post1 = {
    user: {
        id: 1,
        profilePic: 'https://randomuser.me/api/portraits/lego/1.jpg',
        userName: 'Isma el moreno',
    },
    tags: null,
    attachments: [
        {
            id: 1,
            name: 'test.png',
            url: 'https://picsum.photos/1000',
            mime: 'image/png'
        },
        {
            id: 2,
            name: 'test2.png',
            url: 'https://picsum.photos/1000',
            mime: 'image/png'
        },
        {
            id: 3,
            name: 'test3.png',
            url: 'https://picsum.photos/1000',
            mime: 'application/msword'
        },
    ],
    reactions: {
        likes: 430,
        comments: 150
    },
    content: '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias tempore totam blanditiis dignissimos placeat velit eius eveniet cum quibusdam, quia molestias tenetur quam itaque. Ratione, sit? Enim ipsum maxime amet?</p><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias tempore totam blanditiis dignissimos placeat velit eius eveniet cum quibusdam, quia molestias tenetur quam itaque. Ratione, sit? Enim ipsum maxime amet?</p><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias tempore totam blanditiis dignissimos placeat velit eius eveniet cum quibusdam, quia molestias tenetur quam itaque. Ratione, sit? Enim ipsum maxime amet?</p>',
    created_at: '11:36 a. m. · 28 may. 2024',
}

const post2 = {
    user: {
        id: 1,
        profilePic: 'https://randomuser.me/api/portraits/lego/2.jpg',
        userName: 'Lego barman',
    },
    tags: [
        {
            id: 1,
            name: 'Dark Souls',
        },
        {
            id: 2,
            name: 'PS5',
        },
        {
            id: 3,
            name: 'Acción',
        },
    ],
    attachments: [
        {
            id: 1,
            name: 'test.png',
            url: 'https://picsum.photos/1000',
            mime: 'image/png'
        },
    ],
    reactions: {
        likes: 30,
        comments: 10
    },
    content: '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias tempore totam blanditiis dignissimos placeat velit eius eveniet cum quibusdam, quia molestias tenetur quam itaque. Ratione, sit? Enim ipsum maxime amet?</p><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias tempore totam blanditiis dignissimos placeat velit eius eveniet cum quibusdam, quia molestias tenetur quam itaque. Ratione, sit? Enim ipsum maxime amet?</p><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias tempore totam blanditiis dignissimos placeat velit eius eveniet cum quibusdam, quia molestias tenetur quam itaque. Ratione, sit? Enim ipsum maxime amet?</p>',
    created_at: '11:36 a. m. · 28 may. 2024',
}

</script>

<template>
    <div class="overflow-auto">
        <PostItem v-for="post of posts" :key="post.id" :post="post" />
    </div>
</template>

<style scoped></style>