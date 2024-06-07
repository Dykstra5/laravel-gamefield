<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FriendList from '@/Components/app/FriendList.vue';
import CreatePost from '@/Components/app/CreatePost.vue';
import PostList from '@/Components/app/PostList.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { CheckCircleIcon } from '@heroicons/vue/20/solid';
import TagList from '@/Components/app/TagList.vue';

const authUser = usePage().props.auth.user;
const showNotification = ref(false);

const props = defineProps({
    posts: Object,
    usersFollowing: Object,
    tagElement: Object,
    type: String,
    followsTag: Boolean,
    tagsFollowing: Object,
    success: String
});

function followTag() {
    const form = useForm({});
    showNotification.value = true;
    form.post(route('tag.follow', { tagId: props.tagElement.id, type: props.type }), {
        onFinish: function () {
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
        preserveScroll: true
    });
}

function unfollowTag() {
    const form = useForm({});
    showNotification.value = true;
    form.delete(route('tag.unfollow', { tagId: props.tagElement.id, type: props.type }), {
        onFinish: function () {
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
        preserveScroll: true
    });
}
</script>

<template>

    <Head title="GameField" />

    <AuthenticatedLayout>
        <div>
            <div class="container mx-auto px-4 h-full overflow-auto">
                <div class="bg-white rounded-b-lg">
                    <div class="relative">
                        <img :src="tagElement.image_background || '/img/default-cover-red.png'" alt="jajasi"
                            class="w-full h-[500px] object-cover bg-[#922828]">
                        <div v-show="showNotification && success"
                            class="absolute top-2 right-2 flex items-center font-medium text-sm text-white bg-emerald-500 rounded p-3 w-fit">
                            <CheckCircleIcon class="size-8 mr-1 text-white font-black" />
                            {{ success }}
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex items-center justify-between flex-1 p-3">
                            <h2 class="font-black text-4xl">{{ tagElement.name }}</h2>
                            <button @click="followTag" v-if="authUser && !followsTag"
                                class="flex items-start font-bold text-black bg-white hover:bg-gray-200 border-2 border-black transition-all shadow-md rounded px-2 py-1">
                                Añadir a Favoritos
                            </button>
                            <button @click="unfollowTag" v-if="authUser && followsTag"
                                class="flex items-start font-bold text-white bg-black hover:bg-gray-800 border-2 border-black transition-all shadow-md rounded px-2 py-1">
                                Eliminar de Favoritos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid md:grid-cols-12 gap-3 p-4 container mx-auto h-full">
            <div class="md:col-span-3 md:order-1 h-full overflow-hidden">
                <TagList :tagsFollowing="tagsFollowing" />
            </div>

            <div class="md:col-span-3 md:order-3 h-full overflow-hidden">
                <FriendList :usersFollowing="usersFollowing" />
            </div>

            <div class="md:col-span-6 md:order-2 h-full overflow-hidden flex flex-col">
                <CreatePost :defaultElement="tagElement" :type="type" />
                <PostList v-if="posts.data.length > 0" :posts="posts" class="flex-1" />
                <div v-else :posts="posts"
                    class="flex-1 flex justify-center items-center bg-white rounded-md text-center p-3">
                    <p class="text-2xl text-gray-500">No hay posts con este tema asociado</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
