<script setup>
import FriendListItems from './FriendListItems.vue';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid';


defineProps({
    usersFollowing: Object,
});

</script>

<template>
    <div class="bg-white rounded p-4 shadow h-full overflow-hidden">
        <div class="block md:hidden">
            <Disclosure v-slot="{ open }">
                <DisclosureButton class="w-full">
                    <div class="flex justify-between">
                        <h2 class=" text-xl font-bold"> Gente que sigues </h2>
                        <ChevronDownIcon class="w-5 transition-all" :class="open ? '-rotate-180' : ''" />
                    </div>
                </DisclosureButton>

                <DisclosurePanel>
                    <FriendListItems :usersFollowing="usersFollowing" />
                </DisclosurePanel>
            </Disclosure>
        </div>

        <div class="h-full overflow-hidden hidden md:block text-center">
            <h2 class=" text-xl font-bold"> Gente que sigues </h2>
            <FriendListItems :usersFollowing="usersFollowing" />
        </div>
    </div>
</template>

<style scoped></style>