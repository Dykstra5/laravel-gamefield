<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { HeartIcon, ChatBubbleOvalLeftIcon, EllipsisHorizontalIcon, ArrowDownTrayIcon, DocumentDuplicateIcon } from '@heroicons/vue/24/outline';
import { TrashIcon, HeartIcon as HeartIconSolid, ChatBubbleOvalLeftIcon as ChatBubbleOvalLeftIconSolid, EyeIcon, ShieldExclamationIcon } from '@heroicons/vue/24/solid';
import { CursorArrowRaysIcon, TagIcon } from '@heroicons/vue/20/solid';
import { router, usePage, Link } from '@inertiajs/vue3';
import { isImage } from '@/functions';
import axiosClient from '@/axiosClient';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import ReadMoreText from '@/Components/app/ReadMoreText.vue';
import { ref, reactive, computed } from 'vue';

const comment = ref('');

const props = defineProps({
    post: Object,
    user: Object
})

const authUser = usePage().props.auth.user;

const isAdmin = computed(() => authUser && authUser.role_id === 1);

const emit = defineEmits(['attachmentClick']);

function deletePost() {
    if (window.confirm('¿Quieres eliminar este post?')) {
        router.delete(route('post.destroy', props.post.post_id), {
            preserveScroll: true,
        });
    }
}

function deletePostAsAdmin() {
    console.log('deleted by admin')
    // if (window.confirm('¿Quieres eliminar este post?')) {
    //     router.delete(route('post.admin.destroy', props.post.post_id), {
    //         preserveScroll: true,
    //     });
    // }
}

function displaySlider(attachment_index) {
    emit('attachmentClick', props.post, attachment_index)
}

function sendLike() {
    axiosClient.post(route('post.like', props.post.post_id))
        .then(({ data }) => {
            props.post.has_liked = data.has_liked;
            props.post.likes = data.likes;
        });
}

function sendCommentLike(comment) {
    axiosClient.post(route('comment.like', comment.id))
        .then(({ data }) => {
            comment.has_liked = data.has_liked_comment;
            comment.likes = data.commentLikes;
        })
}

function postComment() {
    axiosClient.post(route('post.comment.create', props.post.post_id), { comment: comment.value })
        .then(({ data }) => {
            comment.value = '';
            props.post.last_5_comments.unshift(data);
            props.post.comments++;
        });
}

function deleteComment(comment) {
    if (window.confirm('¿Quieres eliminar este comentario?')) {
        axiosClient.delete(route('post.comment.destroy', comment.id))
            .then(({ data }) => {
                props.post.last_5_comments = props.post.last_5_comments.filter(c => c.id !== comment.id);
                props.post.comments--;
            });
    }
}

function copyUrl() {
    const url = route('post.view', { id: props.post.post_id });
    navigator.clipboard.writeText(url)
        .then(() => {
            console.log('URL copiada al portapapeles');
        })
        .catch(err => {
            console.error('Error al copiar la URL: ', err);
        });
}
</script>

<template>
    <!-- <pre class="text-white">{{ post }}</pre> -->
    <div class=" bg-white rounded px-4 py-2 shadow mb-3">
        <div class="flex justify-between gap-2 mb-3">
            <div class="flex items-center">
                <a :href="route('profile', post.user.username)" class="w-[48px] h-[48px]">
                    <img :src="post.user.avatar_src || '/img/default-avatar-red.png'"
                        class="w-full h-full rounded-full border-2 bg-[#922828] hover:opacity-80 border-red-800 hover:border-red-600 transition-all">
                </a>

                <div class="flex flex-col ml-2">
                    <h4 class="font-bold">
                        <a :href="route('profile', post.user.username)"
                            class=" underline-offset-2 hover:underline transition-all ">
                            {{ post.user.name }}
                        </a>
                    </h4>
                    <p class=" text-sm font-bold">{{ post.title }}</p>
                </div>
            </div>

            <div v-if="authUser">
                <Menu as="div" class="relative flex items-center h-full">
                    <div class="flex items-center h-full">
                        <MenuButton>
                            <EllipsisHorizontalIcon
                                class="size-8 p-1 text-black rounded hover:bg-gray-600/10 transition-all"
                                aria-hidden="true" />
                        </MenuButton>
                    </div>

                    <transition enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0">
                        <MenuItems
                            class="absolute z-10 top-8 right-2 mt-2 p-1 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                            <MenuItem v-slot="{ active }">
                            <button @click="copyUrl" :class="[
                                active ? 'bg-rose-200' : 'text-black',
                                'group flex w-full items-center rounded-md px-2 py-2 text-sm font-black transition-all',
                            ]">
                                <DocumentDuplicateIcon class="mr-2 h-5 w-5 text-black" aria-hidden="true" />
                                Copiar URL
                            </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <Link :href="route('post.view', post.post_id)" :class="[
                                active ? 'bg-rose-200' : 'text-black',
                                'group flex w-full items-center rounded-md px-2 py-2 text-sm font-black transition-all',
                            ]">
                            <EyeIcon class="mr-2 h-5 w-5 text-black" aria-hidden="true" />
                            Abrir post
                            </Link>
                            </MenuItem>
                            <MenuItem v-slot="{ active }" v-if="authUser.id === post.user.id">
                            <button :class="[
                                active ? 'bg-rose-200' : 'text-black',
                                'group flex w-full items-center rounded-md px-2 py-2 text-sm font-black transition-all',
                            ]">
                                <CursorArrowRaysIcon class="mr-2 h-5 w-5 text-black" aria-hidden="true" />
                                Fijar en perfil
                            </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }" v-if="authUser.id === post.user.id">
                            <button @click="deletePost" :class="[
                                active ? 'bg-red-500 text-white' : 'text-red-500',
                                'group flex w-full items-center rounded-md px-2 py-2 text-sm font-black transition-all',
                            ]">
                                <TrashIcon class="mr-2 h-5 w-5" aria-hidden="true" />
                                Eliminar
                            </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }" v-else-if="isAdmin">
                            <button @click="deletePostAsAdmin" :class="[
                                active ? 'bg-red-500 text-white' : 'text-red-500',
                                'group flex w-full items-center rounded-md px-2 py-2 text-sm font-black transition-all',
                            ]">
                                <ShieldExclamationIcon class="mr-2 h-5 w-5" aria-hidden="true" />
                                Eliminar
                            </button>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>
        </div>
        <div class="mb-1">
            <ReadMoreText :content="post.content" contentClass="ck-content-output break-words" />
        </div>
        <div v-if="post.attachments.length > 0" class="grid gap-4" :class="[
            post.attachments.length === 1 ? 'grid-cols-1' : '',
            post.attachments.length === 2 ? 'grid-cols-2' : '',
            post.attachments.length >= 3 ? 'grid-cols-2 md:grid-cols-3' : '',
        ]">
            <template v-for="(attachment, index) of post.attachments">
                <div class="group aspect-square bg-rose-200 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer"
                    @click="displaySlider(index)">
                    <a :href="route('post.download', attachment.attachment_id)"
                        class="absolute right-2 top-2 bg-red-950/60 hover:bg-rose-700 p-1 rounded flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                        <ArrowDownTrayIcon class="size-5 text-white" />
                    </a>
                    <img v-if="isImage(attachment)" :src="attachment.url" :alt="attachment.name"
                        class=" object-contain aspect-square">
                    <template v-else
                        class=" aspect-square bg-blue-100 flex flex-col items-center justify-center  text-gray-500">
                        <DocumentDuplicateIcon class=" max-w-12 max-h-12" />
                        <small>{{ attachment.name }}</small>
                    </template>
                </div>
            </template>
        </div>

        <div class="flex justify-between py-1">
            <div class="flex flex-row justify-center md:justify-start items-center md:items-start">
                <small class="text-gray-600 text-center md:text-left">
                    {{ post.created_at }}
                </small>
            </div>
            <div v-if="post.tags.length > 0" class="flex flex-col md:flex-row justify-start items-center">
                <div class="mr-1 hidden md:block">
                    <small>Temas:</small>
                </div>
                <div v-for="tag in post.tags">
                    <a href="javascript:void(0)"
                        class="flex items-center justify-center rounded-sm px-1 py-1 m-1 text-xs text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
                        :class="[
                            tag.type === 'game' ? 'bg-rose-600 hover:bg-rose-500' : '',
                            tag.type === 'genre' ? 'bg-red-700 hover:bg-red-600' : '',
                            tag.type === 'platform' ? 'bg-pink-700 hover:bg-pink-600' : '',
                            tag.type === 'developer' ? 'bg-fuchsia-700 hover:bg-fuchsia-600' : ''
                        ]">
                        <div class=" min-w-3 min-h-3">
                            <TagIcon class="size-3 mr-1" />
                        </div>
                        <p class="leading-3">{{ tag.name }}</p>
                    </a>
                </div>
            </div>
        </div>

        <Disclosure v-slot="{ open }">
            <div class="flex justify-evenly border-t border-gray-400 pt-2">
                <button v-if="authUser" class="flex justify-center items-center font-black transition-all group"
                    @click="sendLike" :class="[
                        post.has_liked ? 'text-red-800' : ''
                    ]">
                    <!-- me gusta -->
                    <HeartIconSolid v-if="post.has_liked"
                        class="size-8 p-1 text-red-800 transition-all rounded-full group-hover:bg-rose-500/20" />
                    <HeartIcon v-else class="size-8 p-1 transition-all rounded-full group-hover:bg-rose-500/20" />
                    <span class="w-[30px] text-left font-black">{{ post.likes }}</span>
                    <!-- {{ post.reactions.likes }} -->
                </button>
                <div v-else class="flex justify-center items-center font-black transition-all">
                    <!-- me gusta -->
                    <HeartIcon class="size-8 p-1 transition-all rounded-full" />
                    <span class="w-[30px] text-left font-black">{{ post.likes }}</span>
                    <!-- {{ post.reactions.likes }} -->
                </div>
                <DisclosureButton class="flex justify-center items-center transition-all group" :class="[
                    open ? 'text-blue-600' : ''
                ]">
                    <ChatBubbleOvalLeftIconSolid v-if="open"
                        class="size-8 p-1 rounded-full group-hover:bg-blue-500/20" />
                    <ChatBubbleOvalLeftIcon v-else class="size-8 p-1 rounded-full group-hover:bg-blue-500/20" />
                    <span class="w-[30px] text-left font-black">{{ post.comments }}</span>
                </DisclosureButton>
            </div>

            <DisclosurePanel class="text-sm text-gray-500 border-t border-gray-400 mt-2">
                <div class="mt-3">
                    <div class="flex" v-if="authUser">
                        <TextAreaInput v-model="comment" placeholder="Escribe tu comentario aquí" rows="1"
                            class="w-full resize-none rounded-r-none max-h-[150px]"></TextAreaInput>
                        <button @click="postComment" type="submit"
                            class="flex items-center justify-center rounded-l-none rounded-md bg-rose-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                            comentar
                        </button>
                    </div>
                    <div v-if="post.last_5_comments.length > 0" v-for="comment of post.last_5_comments"
                        class=" mt-3 px-4 pt-2 border rounded border-gray-400">
                        <div class=" flex justify-between items-center">
                            <div class="flex items-center">
                                <a href="javascript:void(0)" class="w-[36px] h-[36px]">
                                    <img :src="comment.user.avatar_src || '/img/default-avatar-red.png'"
                                        class="w-full h-full rounded-full border-2 bg-[#922828] hover:opacity-80 border-red-800 hover:border-red-600 transition-all">
                                </a>
                                <h4 class=" ml-2 text-black font-bold flex md:flex-row flex-col">
                                    <a href="javascript:void(0)"
                                        class=" underline-offset-2 hover:underline transition-all ">
                                        {{ comment.user.name }}
                                    </a>
                                </h4>
                            </div>
                            <Menu v-if="authUser && authUser.id === comment.user.id" as="div"
                                class="relative flex items-center h-full">
                                <div class="flex items-center h-full">
                                    <MenuButton>
                                        <EllipsisHorizontalIcon
                                            class="size-8 p-1 text-black rounded hover:bg-gray-600/10 transition-all"
                                            aria-hidden="true" />
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition duration-100 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-75 ease-in"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0">
                                    <MenuItems
                                        class="absolute z-10 top-8 right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                                        <MenuItem v-slot="{ active }">
                                        <button @click="deleteComment(comment)" :class="[
                                            active ? 'bg-red-500 text-white' : 'text-red-500',
                                            'group flex w-full items-center rounded-md px-2 py-2 text-sm font-black transition-all',
                                        ]">
                                            <TrashIcon class="mr-2 h-5 w-5" aria-hidden="true" />
                                            Eliminar
                                        </button>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                        <div class="ml-2">
                            <ReadMoreText :content="comment.comment" contentClass="text-base mt-2 text-black pt-1" />
                            <div class="mt-3 text-black">
                                <small>{{ comment.created_at }}</small>
                            </div>
                        </div>
                        <div class="flex justify-start items-center mt-2 border-t border-gray-400 pl-1">
                            <button v-if="authUser"
                                class="flex justify-center items-center font-black transition-all my-1 group"
                                @click="sendCommentLike(comment)" :class="[
                                    comment.has_liked ? 'text-red-800' : ''
                                ]">
                                <!-- me gusta -->
                                <HeartIconSolid v-if="comment.has_liked"
                                    class="size-7 p-1 transition-all text-red-800 rounded-full group-hover:bg-rose-500/20" />
                                <HeartIcon v-else
                                    class="size-7 p-1 transition-all rounded-full group-hover:bg-rose-500/20" />
                                <span class="w-[30px] text-left font-black">{{ comment.likes }}</span>
                                <!-- {{ post.reactions.likes }} -->
                            </button>
                            <div v-else class="flex justify-center items-center font-black transition-all my-1">
                                <!-- me gusta -->
                                <HeartIcon class="size-7 p-1 transition-all rounded-full" />
                                <span class="w-[30px] text-left font-black">{{ comment.likes }}</span>
                                <!-- {{ post.reactions.likes }} -->
                            </div>
                        </div>
                    </div>
                    <div v-else class="mt-3 py-2 text-center">
                        No hay comentarios
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>

<style scoped></style>