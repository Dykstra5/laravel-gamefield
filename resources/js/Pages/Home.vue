<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GroupList from '@/Components/app/GroupList.vue'
import FriendList from '@/Components/app/FriendList.vue';
import PostList from '@/Components/app/PostList.vue';
import CreatePost from '@/Components/app/CreatePost.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    posts: Object,
    usersFollowing: Object,
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>

    <Head title="GameField" />

    <AuthenticatedLayout>
        <div class="grid md:grid-cols-12 gap-3 p-4 container mx-auto h-full">
            <div class="md:col-span-3 md:order-1 h-full overflow-hidden">
                <GroupList />
            </div>

            <div class="md:col-span-3 md:order-3 h-full overflow-hidden">
                <FriendList :usersFollowing="usersFollowing" />
            </div>

            <div class="md:col-span-6 md:order-2 h-full overflow-hidden flex flex-col">
                <CreatePost />
                <PostList :posts="posts" class="flex-1" />
            </div>
        </div>
    </AuthenticatedLayout>

</template>

<style></style>