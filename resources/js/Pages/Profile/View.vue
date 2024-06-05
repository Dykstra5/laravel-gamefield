<script setup>
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileTabButton from '@/Pages/Profile/Partials/ProfileTabButton.vue'
import Edit from './Edit.vue';
import { computed, ref, watch } from 'vue';
import { CheckIcon, XMarkIcon as XIcon } from '@heroicons/vue/16/solid';
import { XMarkIcon, PhotoIcon, CheckCircleIcon } from '@heroicons/vue/20/solid';
import { ArrowUpOnSquareIcon } from '@heroicons/vue/24/outline';
import { CameraIcon } from '@heroicons/vue/24/solid';
import { useForm } from '@inertiajs/vue3'
import axiosClient from '@/axiosClient';

const showNotification = ref(false);
const authUser = usePage().props.auth.user;
const coverImageSrc = ref("");
const avatarImageSrc = ref("");
const reloadStatus = ref(null);

const isMyProfile = computed(() => authUser && authUser.id === props.user.data.id);
const isAdmin = computed(() => authUser && authUser.role_id === 1);

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    success: {
        type: String,
    },
    user: {
        type: Object
    },
    followsUser: Boolean,
    followers: Number,
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

function ifAvatarChange(e) {
    formImages.avatar = e.target.files[0];

    if (formImages.avatar) {
        const reader = new FileReader();
        reader.onload = function () {
            avatarImageSrc.value = reader.result;
        }
        reader.readAsDataURL(formImages.avatar);
        uploadAvatar();
    }
}

function removeCover() {
    formImages.cover = null;
    coverImageSrc.value = null;
}

function removeAvatar() {
    formImages.avatar = null;
    avatarImageSrc.value = null;
}

function uploadCover() {
    showNotification.value = true;
    formImages.post(route('profile.updateImages'), {
        onFinish: function () {
            removeCover();
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
    });
}

function uploadAvatar() {
    showNotification.value = true;
    formImages.post(route('profile.updateImages'), {
        onFinish: function () {
            removeAvatar();
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
    });
}

function reloadGamesDB() {
    reloadStatus.value = 'loading';
    setTimeout(function () {
        getExternalData();
    }, 10);
}

async function getExternalData() {
    try {
        const response = await axios.get('/games/get-external-data');
        reloadStatus.value = 'done';
        console.log('Datos obtenidos:', response.data);
    } catch (error) {
        reloadStatus.value = 'error';
        console.error('Error al obtener datos externos:', error);
    }
}

function followUser() {
    const form = useForm({});
    showNotification.value = true;
    form.post(route('user.follow', { user: props.user.data.id }), {
        onFinish: function () {
            removeCover();
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
        preserveScroll: true
    });
}

function unfollowUser() {
    const form = useForm({});
    showNotification.value = true;
    form.post(route('user.unfollow', { user: props.user.data.id }), {
        onFinish: function () {
            removeCover();
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
        preserveScroll: true
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 h-full overflow-auto">
            <div class="relative bg-white group">
                <img :src="coverImageSrc || user.data.cover_src || '/img/default-cover-red.png'" alt="jajasi"
                    class="w-full h-[300px] object-cover bg-[#922828]">

                <div class="absolute top-0 right-2 left-2 flex justify-between flex-wrap">
                    <div v-if="isMyProfile" class="mt-2">
                        <button v-if="!coverImageSrc"
                            class="relative flex items-center justify-center rounded bg-rose-600 p-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600 opacity-0 group-hover:opacity-100">
                            <PhotoIcon class="size-4 mr-1 text-white font-black" />
                            Cambiar Cabecera
                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                @change="ifCoverChange">
                        </button>

                        <template v-else>
                            <div class="flex gap-1">
                                <button @click="uploadCover"
                                    class="flex items-start font-bold text-black text-sm bg-white hover:bg-gray-300 transition-all shadow-md rounded p-2 opacity-0 group-hover:opacity-100">
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
                    <div v-show="showNotification && success"
                        class="flex items-center font-medium text-sm text-white bg-emerald-500 rounded p-3 mt-2 w-fit">
                        <CheckCircleIcon class="size-8 mr-1 text-white font-black" />
                        {{ success }}
                    </div>
                    <div v-show="showNotification && (errors.cover || errors.avatar)"
                        class="flex items-center font-medium text-sm text-white bg-red-500 rounded p-3 mt-2 w-fit">
                        <XMarkIcon class="size-8 mr-1 text-white font-black" />
                        {{ errors.cover || errors.avatar }}
                    </div>
                </div>

                <div class="flex">
                    <div
                        class="flex items-center justify-center relative group/avatar ml-8 -mt-[100px] w-[160px] h-[160px]">
                        <img :src="avatarImageSrc || user.data.avatar_src || '/img/default-avatar-red.png'" alt=""
                            class="rounded-full shadow-md w-full h-full bg-gray-950">
                        <button v-if="!avatarImageSrc && isMyProfile"
                            class="absolute left-0 top-0 right-0 bottom-0 flex items-center justify-center text-white bg-black/60 rounded-full p-2 opacity-0 group-hover/avatar:opacity-100 cursor-pointer">
                            <CameraIcon class="size-10 text-gray-200 font-black" />
                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                @change="ifAvatarChange">
                        </button>
                    </div>
                    <div class="flex items-center justify-between flex-1 px-3">
                        <div>
                            <h2 class="font-black text-xl">{{ user.data.name }}</h2>
                            <small class="font-black text-gray-500">Seguidores: {{ followers }}</small>
                        </div>
                        <template v-if="!isMyProfile" >
                            <button @click="followUser" v-if="authUser && !followsUser"
                                class="flex items-start font-bold text-black bg-white hover:bg-gray-200 border-2 border-black transition-all shadow-md rounded px-2 py-1">
                                Seguir
                            </button>
                            <button @click="unfollowUser" v-if="authUser && followsUser"
                                class="flex items-start font-bold text-white bg-black hover:bg-gray-800 border-2 border-black transition-all shadow-md rounded px-2 py-1">
                                Dejar de seguir
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-300">
                <TabGroup>
                    <TabList class="flex pl-4 bg-white rounded-b-lg h-[50px]">
                        <Tab as="template" key="Posts" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Posts" />
                        </Tab>
                        <Tab as="template" key="Juegos" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Juegos" />
                        </Tab>
                        <Tab as="template" key="Siguiendo" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Siguiendo" />
                        </Tab>
                        <Tab as="template" key="Multimedia" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Multimedia" />
                        </Tab>
                        <Tab v-if="isMyProfile" as="template" key="Sobre" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Mi Perfil" />
                        </Tab>
                        <Tab v-if="isAdmin && isMyProfile" as="template" key="Admin" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Admin" />
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel class="rounded bg-white p-3">
                            Posts
                        </TabPanel>
                        <TabPanel class="rounded bg-white p-3">
                            Juegos
                        </TabPanel>
                        <TabPanel class="rounded bg-white p-3">
                            Siguiendo
                        </TabPanel>
                        <TabPanel class="rounded bg-white p-3">
                            Multimedia
                        </TabPanel>
                        <TabPanel v-if="isMyProfile" class="rounded">
                            <Edit :must-verify-email="mustVerifyEmail" :status="status" />
                        </TabPanel>
                        <TabPanel v-if="isAdmin && isMyProfile" class="rounded">
                            <div class="w-full bg-white p-8 rounded-lg">
                                <h2 class="text-xl font-black mb-1">Reload videogames database information</h2>
                                <p class="text-sm">Esta función permite volver a generar los datos relacionados con los
                                    videojuegos
                                </p>
                                <div class="flex mt-6">
                                    <button v-if="isAdmin" @click="reloadGamesDB"
                                        class="relative flex items-center justify-center rounded bg-rose-600 p-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                                        Reload Data
                                    </button>
                                    <div class="flex justify-center items-center ml-2">
                                        <img v-if="reloadStatus === 'loading'" class="size-6"
                                            src="/img/loading-green-loading.gif">
                                        <CheckIcon v-if="reloadStatus === 'done'" class="size-8 text-green-600" />
                                        <XIcon v-if="reloadStatus === 'error'" class="size-8 text-red-600" />
                                    </div>
                                </div>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
