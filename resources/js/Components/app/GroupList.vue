<script setup>
import GroupListItems from '@/Components/app/GroupListItems.vue';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'

</script>

<template>
    <div class="bg-white rounded p-4 shadow h-full overflow-hidden">
        <div class="block md:hidden">
            <Disclosure v-slot="{ open }">
                <DisclosureButton class="w-full">
                    <div class="flex justify-between">
                        <h2 class=" text-xl font-bold"> Mis temas favoritos </h2>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 transition-all" :class="open ? '-rotate-180' : ''">
                            <path fill-rule="evenodd"
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </DisclosureButton>

                <DisclosurePanel v-else">
                    <GroupListItems />
                </DisclosurePanel>
            </Disclosure>
        </div>

        <div class="h-full overflow-hidden hidden md:block">
            <h2 class=" text-xl font-bold"> Mis temas favoritos </h2>
            <GroupListItems />
        </div>
    </div>
</template>

<style scoped></style>