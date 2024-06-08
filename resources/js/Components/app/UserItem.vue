<script setup>
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const authUser = usePage().props.auth.user;
const followsUser = ref(true);

defineProps({
    user: Object,
    isMyProfile: Boolean
});

const emits = defineEmits(['followUser', 'unfollowUser']);

function follow() {
    followsUser.value = true;
    emits('followUser');
}

function unfollow() {
    followsUser.value = false;
    emits('unfollowUser');
}
</script>

<template>
    <div
        class="flex items-center justify-between border-[4px] bg-gray-100 hover:bg-gray-100 border-white hover:border-gray-300 rounded-lg p-2 group transition-all">
        <Link :href="route('profile', user.username)" class="w-full">
        <div class="flex flex-row items-center justify-start md:justify-around lg:justify-start">
            <div class="w-[38px] h-[38px] lg:w-[48px] lg:h-[48px]">
                <img :src="user.avatar_src || '/img/default-avatar-red.png'"
                    class="w-full h-full rounded-full border-2 bg-[#922828] border-red-800  transition-all">
            </div>
            <div class="ml-2 md:ml-0 lg:ml-2">
                <span class="font-bold">
                    {{ user.name }}
                </span>
            </div>
        </div>
        </Link>
        <template v-if="isMyProfile">
            <button @click.prevent.stop="follow" v-if="authUser && !followsUser"
                class="flex items-start font-bold text-black bg-white hover:bg-gray-200 border-2 border-black transition-all shadow-md rounded px-2 py-1">
                Seguir
            </button>
            <button @click.prevent.stop="unfollow" v-if="authUser && followsUser"
                class="flex items-start font-bold text-white bg-black hover:bg-gray-800 border-2 border-black transition-all shadow-md rounded px-2 py-1">
                Dejar de seguir
            </button>
        </template>
    </div>
</template>

<style scoped></style>