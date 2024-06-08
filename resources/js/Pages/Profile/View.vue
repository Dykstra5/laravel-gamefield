<script setup>
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileTabButton from '@/Pages/Profile/Partials/ProfileTabButton.vue'
import ProfileTabButtonMobile from '@/Pages/Profile/Partials/ProfileTabButtonMobile.vue'
import Edit from './Edit.vue';
import { computed, ref, watch, onMounted, onUnmounted } from 'vue';
import { CheckIcon, XMarkIcon as XIcon } from '@heroicons/vue/16/solid';
import { XMarkIcon, PhotoIcon, CheckCircleIcon } from '@heroicons/vue/20/solid';
import { ArrowUpOnSquareIcon, Bars3Icon } from '@heroicons/vue/24/outline';
import { CameraIcon } from '@heroicons/vue/24/solid';
import { useForm, Link } from '@inertiajs/vue3'
import CreatePost from '@/Components/app/CreatePost.vue';
import PostList from '@/Components/app/PostList.vue';
import DeletedPostList from '@/Components/app/DeletedPostList.vue';
import UserItem from '@/Components/app/UserItem.vue';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import SelectInput from '@/Components/SelectInput.vue';
import AttachmentsModal from '@/Components/app/AttachmentsModal.vue';

const showNotification = ref(false);
const userCreated = ref(false);
const authUser = usePage().props.auth.user;
const coverImageSrc = ref("");
const avatarImageSrc = ref("");
const reloadStatus = ref(null);
const searchUser = ref('');
const searchUsersResult = ref([]);
let allUsersFollowing = ref(null);
const attachmentIndex = ref(null);
const showAttachmentsModal = ref(false);

const isMyProfile = computed(() => authUser && authUser.id === props.user.data.id);
const isAdmin = computed(() => authUser && authUser.role_id === 1);
userCreated.value = computed(() => props.userCreated && props.userCreated === true);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

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
    usersFollowing: Object,
    posts: Object,
    roles: Array,
    userCreatedMessage: String,
    deletedPosts: Object,
    allAttachments: Object,
    errors: Object
});

const isMobile = ref(window.innerWidth < 768); // Usar 768px como punto de corte para dispositivos móviles

const updateIsMobile = () => {
    isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
    window.addEventListener('resize', updateIsMobile);
});

onUnmounted(() => {
    window.removeEventListener('resize', updateIsMobile);
});


allUsersFollowing = props.usersFollowing.data;

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
    } catch (error) {
        reloadStatus.value = 'error';
    }
}

function followUser() {
    const form = useForm({});
    showNotification.value = true;
    form.post(route('user.follow', { user: props.user.data.id }), {
        onFinish: function () {
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
    form.delete(route('user.unfollow', { user: props.user.data.id }), {
        onFinish: function () {
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
        preserveScroll: true
    });
}

function follow(user) {
    const form = useForm({});
    showNotification.value = true;
    form.post(route('user.follow', { user: user.id }), {
        onFinish: function () {
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
        preserveScroll: true
    });
}

function unfollow(user) {
    const form = useForm({});
    showNotification.value = true;
    form.post(route('user.unfollow', { user: user.id }), {
        onFinish: function () {
            setTimeout(function () {
                showNotification.value = false;
            }, 3000);
        },
        preserveScroll: true
    });
}

const submit = () => {
    form.post(route('register'), {
        onSuccess: () => {
            userCreated.value = true;
            form.reset();
        },
        onError: () => {
            form.reset('password', 'password_confirmation');
        }
    });
};

async function searchUserToDelete() {
    if (searchUser.value.length !== 0) {
        try {
            const response = await axios.get(`/user/${searchUser.value}/search`)
            searchUsersResult.value = response.data;
        } catch (error) {
            console.error('Error fetching search results:', error);
            searchUsersResult.value = [];
        }
    }
}

function deleteUser(user) {
    if (window.confirm('¿Quieres eliminar este usuario definitivamente?')) {
        router.delete(route('user.destroy', user.id), {
            preserveScroll: true,
        });
        searchUserToDelete();
    }
}

function displaySlider(index) {
    attachmentIndex.value = index;
    showAttachmentsModal.value = true;
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

                <div class="flex flex-col md:flex-row gap-2 md:gap-0">
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
                        <template v-if="!isMyProfile">
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
                    <div v-if="isMobile" class="bg-white rounded-b-lg">
                        <Disclosure v-slot="{ open }">
                            <DisclosureButton class="flex justify-center w-full md:text-white p-2">
                                <Bars3Icon class="size-10 text-black" />
                            </DisclosureButton>

                            <DisclosurePanel>
                                <TabList
                                    class="flex flex-col items-start rounded-b-lg w-full border-t border-gray-300 pb-3">
                                    <Tab as="template" key="Posts" v-slot="{ selected }">
                                        <ProfileTabButtonMobile :selected="selected" text="Posts" />
                                    </Tab>
                                    <Tab as="template" key="Siguiendo" v-slot="{ selected }">
                                        <ProfileTabButtonMobile :selected="selected" text="Siguiendo" />
                                    </Tab>
                                    <Tab as="template" key="Multimedia" v-slot="{ selected }">
                                        <ProfileTabButtonMobile :selected="selected" text="Multimedia" />
                                    </Tab>
                                    <Tab v-if="isMyProfile" as="template" key="Sobre" v-slot="{ selected }">
                                        <ProfileTabButtonMobile :selected="selected" text="Mi Perfil" />
                                    </Tab>
                                    <Tab v-if="isAdmin && isMyProfile" as="template" key="Deleted"
                                        v-slot="{ selected }">
                                        <ProfileTabButtonMobile :selected="selected" text="Eliminados" />
                                    </Tab>
                                    <Tab v-if="isAdmin && isMyProfile" as="template" key="Admin" v-slot="{ selected }">
                                        <ProfileTabButtonMobile :selected="selected" text="Admin" />
                                    </Tab>
                                </TabList>
                            </DisclosurePanel>
                        </Disclosure>
                    </div>
                    <TabList v-else class="md:flex pl-4 bg-white rounded-b-lg h-[50px]">
                        <Tab as="template" key="Posts" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Posts" />
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
                        <Tab v-if="isAdmin && isMyProfile" as="template" key="deleted" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Eliminados" />
                        </Tab>
                        <Tab v-if="isAdmin && isMyProfile" as="template" key="Admin" v-slot="{ selected }">
                            <ProfileTabButton :selected="selected" text="Admin" />
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-3 rounded-lg overflow-hidden">
                        <TabPanel key="Posts">
                            <div>
                                <CreatePost v-if="isMyProfile" />
                            </div>
                            <PostList v-if="posts.data.length > 0" :posts="posts" class="flex-1" />
                            <div v-else class="text-lg text-gray-600 text-center rounded bg-white p-3">
                                Este usuario no tiene posts
                            </div>
                        </TabPanel>
                        <TabPanel key="Siguiendo">
                            <div v-if="allUsersFollowing.length > 0" class="grid grid-cols-12 gap-3">
                                <UserItem v-for="user of allUsersFollowing" :user="user" :isMyProfile="isMyProfile"
                                    @followUser="follow(user)" @unfollowUser="unfollow(user)"
                                    class="col-span-12 md:col-span-6" />
                            </div>
                            <div v-else class="text-lg text-black text-center rounded bg-white p-3">
                                Este usuario no sigue a nadie
                            </div>
                        </TabPanel>
                        <TabPanel key="Multimedia">
                            <div v-if="allAttachments.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-1 rounded bg-white p-3">
                                <div v-for="(attachment, index) of allAttachments.data" class="bg-gray-200 cursor-pointer"  @click="displaySlider(index)">
                                    <img :src="attachment.url" :alt="attachment.name"
                                        class=" object-contain aspect-square">
                                </div>
                            </div>
                            <div v-else class="text-lg text-black text-center rounded bg-white p-3">
                                Este usuario no tiene imágenes
                            </div>
                        </TabPanel>
                        <TabPanel key="Sobre" v-if="isMyProfile" class="rounded">
                            <Edit :must-verify-email="mustVerifyEmail" :status="status" />
                        </TabPanel>
                        <TabPanel key="Juegos" v-if="isAdmin && isMyProfile">
                            <DeletedPostList v-if="deletedPosts.data.length > 0" :deleted-posts="deletedPosts.data"
                                class="flex-1" />
                            <div v-else class="text-lg text-gray-600 text-center rounded bg-white p-3">
                                No hay posts eliminados
                            </div>
                        </TabPanel>
                        <TabPanel key="Admin" v-if="isAdmin && isMyProfile" class="rounded pb-8">
                            <div class="bg-white p-8 rounded-lg mb-3">
                                <h2 class="text-xl font-black mb-1">Crear Nuevo Usuario</h2>
                                <form @submit.prevent="submit" class="max-w-[400px]">
                                    <div>
                                        <InputLabel for="name" value="Name" :class="'text-gray-800'" />

                                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                                            required autofocus autocomplete="name" />

                                        <InputError class="mt-2 text-black" :message="form.errors.name" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="email" value="Email" :class="'text-gray-800'" />

                                        <TextInput id="email" type="email" class="mt-1 block w-full"
                                            v-model="form.email" required autocomplete="username" />

                                        <InputError class="mt-2 text-black" :message="form.errors.email" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="password" value="Password" :class="'text-gray-800'" />

                                        <TextInput id="password" type="password" class="mt-1 block w-full"
                                            v-model="form.password" required autocomplete="new-password" />

                                        <InputError class="mt-2 text-black" :message="form.errors.password" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="password_confirmation" value="Confirm Password"
                                            :class="'text-gray-800'" />

                                        <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                                            v-model="form.password_confirmation" required autocomplete="new-password" />

                                        <InputError class="mt-2 text-black"
                                            :message="form.errors.password_confirmation" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="role" value="User Rol" :class="'text-gray-800'" />

                                        <SelectInput id="role" :options="roles" v-model="form.role" required
                                            autocomplete="role" />

                                        <InputError class="mt-2 text-black" :message="form.errors.role" />
                                    </div>

                                    <div class="flex items-center justify-start mt-4">
                                        <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                            class="relative flex items-center justify-center rounded bg-rose-600 p-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                                            Registrar usuario
                                        </button>
                                    </div>
                                </form>
                                <div v-if="userCreated && success" class=" p-3 my-3 bg-emerald-300 w-fit rounded">
                                    {{ success }}
                                </div>
                            </div>
                            <div class="bg-white p-8 rounded-lg mb-3">
                                <h2 class="text-xl font-black mb-1">Eliminar usuario existente</h2>
                                <p class="text-sm mb-1 text-gray-700">Pulsa enter para buscar</p>
                                <TextInput class="mt-1 block w-full" v-model="searchUser"
                                    @keyup.enter="searchUserToDelete" />
                                <div v-if="searchUsersResult.usersSearch && Object.keys(searchUsersResult.usersSearch).length > 0"
                                    class="my-3 max-h-[400px] overflow-auto">
                                    <div v-for="user of searchUsersResult.usersSearch">
                                        <div
                                            class="flex flex-col md:flex-row items-center justify-between flex-wrap border-[4px] bg-white hover:bg-gray-100 border-white hover:border-gray-300 rounded-lg p-2 group transition-all">
                                            <Link :href="route('profile', user)" class="flex items-center self-start">
                                            <a class="w-[48px] h-[48px]">
                                                <img :src="user.avatar_src || '/img/default-avatar-red.png'"
                                                    class="w-full h-full rounded-full border-2 bg-[#922828] border-red-800  transition-all">
                                            </a>
                                            <div class="flex flex-col items-start ml-2">
                                                <h4 class="font-bold">
                                                    <a
                                                        class=" underline-offset-2 group-hover:underline transition-all ">
                                                        {{ user.name }}
                                                    </a>
                                                </h4>
                                                <h4 class="font-bold">
                                                    <a class=" underline-offset-2 text-gray-500">
                                                        {{ user.username }}
                                                    </a>
                                                </h4>
                                            </div>
                                            </Link>
                                            <button @click="deleteUser(user)" v-if="authUser && isAdmin"
                                                class="px-4 py-2 md:w-fit w-full mt-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Eliminar Usuario
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        <AttachmentsModal :attachments="allAttachments.data || []"
            v-model:attachment_index="attachmentIndex" v-model="showAttachmentsModal" />
    </AuthenticatedLayout>
</template>
