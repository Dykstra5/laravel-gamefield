<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FriendList from '@/Components/app/FriendList.vue';
import CreatePost from '@/Components/app/CreatePost.vue';
import PostList from '@/Components/app/PostList.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { CheckCircleIcon } from '@heroicons/vue/20/solid';
import TagList from '@/Components/app/TagList.vue';
import UserItem from '@/Components/app/UserItem.vue';

const authUser = usePage().props.auth.user;
const showNotification = ref(false);

const props = defineProps({
    users: Object,
    genres: Object,
    games: Object,
    platforms: Object,
    developers: Object,
    usersFollowing: Object,
    tagsFollowing: Object,
});

</script>

<template>

    <Head title="GameField" />

    <AuthenticatedLayout>
        <div class="grid md:grid-cols-12 gap-3 p-4 container mx-auto h-full">
            <div class="md:col-span-3 md:order-1 h-full overflow-hidden">
                <TagList :tagsFollowing="tagsFollowing" />
            </div>

            <div class="md:col-span-3 md:order-3 h-full overflow-hidden">
                <FriendList :usersFollowing="usersFollowing" />
            </div>

            <div class="md:col-span-6 md:order-2 h-full overflow-hidden">
                <div class="xl:grid grid-cols-1 xl:grid-cols-2 gap-3 h-full bg-white rounded-lg p-3 overflow-auto">
                    <div class="h-fit">
                        <h3 v-if="users.data.length > 0" class="text-xl">
                            Usuarios
                        </h3>
                        <h3 v-else class="text-xl">
                            No se ha encontrado usuarios
                        </h3>
                        <UserItem v-for="user of users.data" :user="user" :is-my-profile="true" />
                    </div>
                    <div>
                        <div v-if="games.length > 0" class="mb-10">
                            <h3 class="text-xl ">
                                Juegos
                            </h3>
                            <a :href="route('tag', { type: 'game', slug: tag.slug })" v-for="tag of games"
                                class="my-1 p-1 rounded-md bg-rose-600 hover:bg-rose-500 hover:underline block text-white">{{
                                    tag.name
                                }}</a>
                        </div>
                        <div v-else class="mb-5">
                            <h3 class="text-xl">
                                No se ha encontrado Juegos
                            </h3>
                        </div>
                        <div v-if="genres.length > 0" class="mb-10">
                            <h3 class="text-xl ">
                                Géneros
                            </h3>
                            <a :href="route('tag', { type: 'genre', slug: tag.slug })" v-for="tag of genres"
                                class="my-1 p-1 rounded-md bg-red-700 hover:bg-red-600 hover:underline block text-white">{{
                                    tag.name
                                }}</a>
                        </div>
                        <div v-else class="mb-5">
                            <h3 class="text-xl">
                                No se ha encontrado Géneros
                            </h3>
                        </div>
                        <div v-if="platforms.length > 0" class="mb-10">
                            <h3 class="text-xl ">
                                Plataformas
                            </h3>
                            <a :href="route('tag', { type: 'platform', slug: tag.slug })" v-for="tag of platforms"
                                class="my-1 p-1 rounded-md bg-pink-700 hover:bg-pink-600 hover:underline block text-white">{{
                                    tag.name }}</a>
                        </div>
                        <div v-else class="mb-5">
                            <h3 class="text-xl">
                                No se ha encontrado Plataformas
                            </h3>
                        </div>
                        <div v-if="developers.length > 0" class="mb-10">
                            <h3 class="text-xl ">
                                Desarrolladores
                            </h3>
                            <a :href="route('tag', { type: 'developer', slug: tag.slug })" v-for="tag of developers"
                                class="my-1 p-1 rounded-md bg-fuchsia-700 hover:bg-fuchsia-600 hover:underline block text-white">{{
                                    tag.name }}</a>
                        </div>
                        <div v-else class="mb-5">
                            <h3 class="text-xl">
                                No se ha encontrado Desarrolladores
                            </h3>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
