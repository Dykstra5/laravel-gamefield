<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { HeartIcon, ChatBubbleOvalLeftIcon, EllipsisHorizontalIcon } from '@heroicons/vue/24/outline';
import { TrashIcon } from '@heroicons/vue/24/solid';
import { CursorArrowRaysIcon } from '@heroicons/vue/20/solid';
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { router } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
    id: Number,
})

function isImage(attachment) {
    let mimeType = attachment.mime.split('/');

    if (mimeType[0] == 'image') {
        return true;
    }

    return false;
}

function deletePost() {
    if (window.confirm('¿Quieres eliminar este post?')) {
        router.delete(route('post.destroy', props.post.post_id), {
            preserveScroll: true,
        });
    }
}

</script>

<template>
    <div class=" bg-white rounded p-4 shadow mb-3">
        <div class="flex justify-between gap-2 mb-3">
            <div class="flex items-center">
                <a href="javascript:void(0)">
                    <img :src="post.user.avatar_src"
                        class="w-[48px] h-[48px] rounded-full border-2 hover:opacity-80 border-red-800 hover:border-red-600 transition-all">
                </a>

                <h4 class=" ml-2 font-bold flex md:flex-row flex-col">
                    <a href="javascript:void(0)" class=" underline-offset-2 hover:underline transition-all ">
                        {{ post.user.name }}
                    </a>
                    <div v-if="post.title" class="flex items-center">
                        <ChevronDownIcon class="size-5 -rotate-90 hidden md:block" />
                        <p>{{ post.title }}</p>
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
                            class="absolute top-8 right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
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
        <div v-if="post.attachments" class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <template v-for="attachment of post.attachments">
                <div
                    class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative">
                    <button
                        class="absolute right-2 top-2 bg-red-900 hover:bg-red-950 p-1 rounded flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                        <!-- descarga -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </button>
                    <img v-if="isImage(attachment)" :src="attachment.url" :alt="attachment.name" class=" aspect-square">
                    <template v-else
                        class=" aspect-square bg-blue-100 flex flex-col items-center justify-center  text-gray-500">
                        <!-- archivo/fichero -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class=" max-w-12 max-h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <small>{{ attachment.name }}</small>
                    </template>

                </div>

            </template>
        </div>
        <div class="flex justify-evenly">
            <button class="mx-5 my-3 flex justify-center gap-2">
                <!-- me gusta -->
                <HeartIcon class="size-6" />
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