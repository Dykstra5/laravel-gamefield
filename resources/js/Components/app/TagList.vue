<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import TagListItems from './TagListItems.vue';

defineProps({
    tagsFollowing: Object,
});

</script>

<template>
    <div class="bg-white rounded p-4 shadow h-full overflow-hidden">
        <div class="block md:hidden">
            <Disclosure v-slot="{ open }">
                <DisclosureButton class="w-full">
                    <div class="flex justify-between">
                        <h2 class=" text-xl font-bold"> Mis temas favoritos </h2>
                        <ChevronDownIcon class="w-5 transition-all" :class="open ? '-rotate-180' : ''" />
                    </div>
                </DisclosureButton>

                <DisclosurePanel>
                    <TagListItems :tagsFollowing="tagsFollowing" />
                </DisclosurePanel>
            </Disclosure>
        </div>

        <div class="h-full overflow-hidden hidden md:block text-center">
            <h2 class="text-xl font-bold"> Mis temas favoritos </h2>
            <TagListItems :tagsFollowing="tagsFollowing" />
        </div>
    </div>
</template>

<style scoped></style>