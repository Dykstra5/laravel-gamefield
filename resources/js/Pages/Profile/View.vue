<script setup>
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileTabButton from '@/Pages/Profile/Partials/ProfileTabButton.vue'
import Edit from './Edit.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { computed, ref, watch } from 'vue';
import { XMarkIcon, PhotoIcon, CheckCircleIcon } from '@heroicons/vue/20/solid';
import { ArrowUpOnSquareIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3'

const showNotification = ref(true);
const authUser = usePage().props.auth.user;
const coverImageSrc = ref("");
const avatarImageSrc = ref("");

const isMyProfile = computed(() => authUser && authUser.id == props.user.data.id);

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object
    },
    errors: Object
});

const formImages = useForm({
    cover: null,
    avatar: null,
});

function ifCoverChange(e) {
    formImages.cover = e.target.files[0];

    if (formImages.cover) {
        const reader = new FileReader();
        reader.onload = function () {
            coverImageSrc.value = reader.result;
        }
        reader.readAsDataURL(formImages.cover);
    }
}

function removeCover() {
    formImages.cover = null;
    coverImageSrc.value = null;
}

function uploadCover() {
    showNotification.value = true;
    formImages.post(route('profile.updateCover'), {
        onSuccess: function () {
            removeCover();
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
        onError: function () {
            removeCover();
            console.log('Error');
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
    });
}
</script>

<template>
    <pre>{{ errors }}</pre>
    <pre>Status: {{ status }}</pre>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 h-full overflow-auto">
            <div class="relative bg-white group">
                <img :src="coverImageSrc || user.data.cover_src || '/img/default-cover-red.png'" alt="jajasi"
                    class="w-full h-[300px] object-cover">

                <div class="absolute top-0 right-2 left-2 flex justify-between flex-wrap">
                    <div class="mt-2">
                        <button v-if="!coverImageSrc"
                            class="relative flex items-center text-white font-black text-sm bg-gradient-to-tr from-rose-900 to-red-500 hover:to-red-600 hover:from-red-600 transition-all shadow rounded border-2 border-red-600 p-2 opacity-0 group-hover:opacity-100">
                            <PhotoIcon class="size-4 mr-1 text-white font-black" />
                            Cambiar Cabecera
                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                @change="ifCoverChange">
                        </button>

                        <template v-else>
                            <div class="flex gap-1">
                                <button @click="uploadCover"
                                    class="flex items-start text-black font-black text-sm bg-white hover:bg-gray-300 transition-all shadow-md rounded p-2 opacity-0 group-hover:opacity-100">
                                    <ArrowUpOnSquareIcon class="size-4 mr-1 text-black font-black" />
                                    Guardar
                                </button>
                                <button @click="removeCover"
                                    class="flex items-center text-white font-black text-sm bg-black hover:bg-gray-800 transition-all shadow-md rounded p-2 opacity-0 group-hover:opacity-100">
                                    <XMarkIcon class="size-5 mr-1 text-white font-black" />
                                    Cancelar
                                </button>
                            </div>
                        </template>
                    </div>
                    <div v-show="showNotification && status === 'cover-image-updated'" class="flex items-center font-medium text-sm text-white bg-emerald-500 rounded p-3 mt-2 w-fit">
                        <CheckCircleIcon class="size-8 mr-1 text-white font-black" />
                        La cabecera se ha actualizado correctamente
                    </div>
                    <div v-show="showNotification && errors.cover" class="flex items-center font-medium text-sm text-white bg-red-500 rounded p-3 mt-2 w-fit">
                        <XMarkIcon class="size-8 mr-1 text-white font-black" />
                        {{ errors.cover }}
                    </div>
                </div>

                <div class="flex">
                    <img :src="avatarImageSrc || user.data.avatar_src || '/img/default-avatar-red.png'" alt=""
                        class="rounded-full shadow-md w-[160px] ml-8 -mt-[100px]">
                    <div class="flex items-center justify-between flex-1 p-3">
                        <h2 class="font-black text-xl">{{ user.name }}</h2>
                        <PrimaryButton v-if="isMyProfile">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-5 mr-1">
                                <path
                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                            </svg>
                            editar perfil
                        </PrimaryButton>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-300">
                <TabGroup>
                    <TabList class="flex pl-4 bg-white rounded-b-lg h-[50px]">
                        <Tab v-if="isMyProfile" as="template" key="Sobre" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Sobre mi" />
                        </Tab>
                        <Tab as="template" key="Posts" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Posts" />
                        </Tab>
                        <Tab as="template" key="Juegos" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Juegos" />
                        </Tab>
                        <Tab v-if="isMyProfile" as="template" key="Amigos" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Amigos" />
                        </Tab>
                        <Tab as="template" key="Multimedia" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Multimedia" />
                        </Tab>

                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel v-if="isMyProfile" class="rounded">
                            <Edit :must-verify-email="mustVerifyEmail" :status="status" />
                        </TabPanel>
                        <TabPanel class="rounded bg-white p-3">
                            Posts
                        </TabPanel>
                        <TabPanel class="rounded bg-white p-3">
                            Juegos
                        </TabPanel>
                        <TabPanel v-if="isMyProfile" class="rounded bg-white p-3">
                            Amigos
                        </TabPanel>
                        <TabPanel class="rounded bg-white p-3">
                            Multimedia
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
