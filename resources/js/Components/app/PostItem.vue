<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { HeartIcon, ChatBubbleOvalLeftIcon, EllipsisHorizontalIcon, ArrowDownTrayIcon, DocumentDuplicateIcon } from '@heroicons/vue/24/outline';
import { TrashIcon, HeartIcon as HeartIconSolid } from '@heroicons/vue/24/solid';
import { CursorArrowRaysIcon } from '@heroicons/vue/20/solid';
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { router } from '@inertiajs/vue3';
import { isImage } from '@/functions';
import axiosClient from '@/axiosClient';

const props = defineProps({
    post: Object,
    attachment: Object,
    id: Number,
})

const emit = defineEmits(['attachmentClick']);

function deletePost() {
    if (window.confirm('¿Quieres eliminar este post?')) {
        router.delete(route('post.destroy', props.post.post_id), {
            preserveScroll: true,
        });
    }
}

function displaySlider(attachment_index) {
    emit('attachmentClick', props.post, attachment_index)
}

function sendLike() {
    axiosClient.post(route('post.like', props.post.post_id))
        .then(({data}) => {
            props.post.has_liked = data.has_liked;
            props.post.likes = data.likes;
        });
}
</script>

<template>
    <div class=" bg-white rounded p-4 shadow mb-3">
        <div class="flex justify-between gap-2 mb-3">
            <div class="flex items-center">
                <a href="javascript:void(0)" class="w-[48px] h-[48px]">
                    <img :src="post.user.avatar_src || '/img/default-avatar-red.png'"
                        class="w-full h-full rounded-full border-2 bg-[#922828] hover:opacity-80 border-red-800 hover:border-red-600 transition-all">
                </a>

                <h4 class=" ml-2 font-bold flex md:flex-row flex-col">
                    <a href="javascript:void(0)" class=" underline-offset-2 hover:underline transition-all ">
                        {{ post.user.name }}
                    </a>
                    <div v-if="post.title" class="flex items-center">
                        <ChevronDownIcon class="size-5 -rotate-90 hidden md:block" />
                        <p class=" text-sm font-bold">{{ post.title }}</p>
                    </div>
                </h4>
            </div>

            <div>
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
                            class="absolute z-10 top-8 right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                            <MenuItem v-slot="{ active }">
                            <button :class="[
                                active ? 'bg-rose-200' : 'text-black',
                                'group flex w-full items-center rounded-md px-2 py-2 text-sm font-black transition-all',
                            ]">
                                <CursorArrowRaysIcon class="mr-2 h-5 w-5 text-black" aria-hidden="true" />
                                Fijar en perfil
                            </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <button @click="deletePost" :class="[
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
        </div>
        <div class="mb-1">
            <Disclosure v-if="post.content.length > 300" v-slot="{ open }">
                <div v-if="!open" class="ck-content-output break-words" v-html="post.content.substring(0, 300)" />
                <DisclosurePanel v-else>
                    <div class="ck-content-output break-words" v-html="post.content" />
                </DisclosurePanel>

                <div class="flex justify-end">
                    <DisclosureButton class=" font-black text-red-600 hover:underline">
                        <small>{{ open ? 'Mostrar menos' : 'Mostrar Más' }}</small>
                    </DisclosureButton>
                </div>
            </Disclosure>
            <div v-else class="ck-content-output break-words" v-html="post.content" />
        </div>
        <div v-if="post.attachments" class="grid gap-4" :class="[
            post.attachments.length === 1 ? 'grid-cols-1' : '',
            post.attachments.length === 2 ? 'grid-cols-2' : '',
            post.attachments.length >= 3 ? 'grid-cols-2 md:grid-cols-3' : '',
        ]">
            <template v-for="(attachment, index) of post.attachments">
                <div class="group aspect-square bg-rose-200 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer"
                    @click="displaySlider(index)">
                    <a :href="route('post.download', attachment.attachment_id)"
                        class="absolute right-2 top-2 bg-red-900 hover:bg-red-950 p-1 rounded flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
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
        <div class="flex justify-evenly">
            <button class="my-3 flex justify-center gap-2 font-black transition-all" @click="sendLike" :class="[
                post.has_liked ? 'text-red-800' : ''
            ]">
                <!-- me gusta -->
                <HeartIconSolid v-if="post.has_liked" class="size-6" />
                <HeartIcon v-else class="size-6" />
                <span class="w-[30px] text-left">{{ post.likes }}</span>
                <!-- {{ post.reactions.likes }} -->
            </button>
            <button class="mx-5 my-3 flex justify-center gap-2">
                <!-- comentario -->
                <ChatBubbleOvalLeftIcon class="size-6" />
                <!-- {{ post.reactions.comments }} -->
            </button>
        </div>
        <div class="flex justify-between border-t">
            <div>
                <small class=" text-gray-600">
                    {{ post.created_at }}
                </small>
            </div>
            <div v-if="post.tags" class="flex flex-row justify-start items-center">
                <div class="mr-1">
                    <small>Temas:</small>
                </div>
                <div v-for="tag in post.tags"
                    class="mr-1 bg-red-600 rounded-sm h-4 pr-1 pl-1 hover:bg-red-700 text-white cursor-pointer">
                    <a href="javascript:void(0)">
                        <small class="block text leading-4">
                            {{ tag.name }}
                        </small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>