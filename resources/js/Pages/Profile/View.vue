<script setup>
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProfileTabButton from '@/Pages/Profile/Partials/ProfileTabButton.vue'
import Edit from './Edit.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { computed } from 'vue';

const authUser = usePage().props.auth.user;

const isMyProfile = computed(() => authUser && authUser.id == props.user.id);

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
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 h-full overflow-auto">
            <div class="relative bg-white">
                <img src="https://pbs.twimg.com/profile_banners/4459154314/1706700329/1500x500" alt="jajasi"
                    class="w-full h-[300px] object-cover">
                <div class="flex">
                    <img src="https://pbs.twimg.com/profile_images/999758305080688641/puc5HQeL_400x400.jpg" alt=""
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
