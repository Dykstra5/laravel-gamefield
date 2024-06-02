<script setup>

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";

defineProps({
    content: String,
    contentClass: String
})

</script>

<template>
    <Disclosure v-if="content.length > 300" v-slot="{ open }">
        <div v-if="!open" :class="contentClass" v-html="content.substring(0, 300)" />

        <DisclosurePanel v-else>
            <div :class="contentClass" v-html="content" />
        </DisclosurePanel>

        <div class="flex justify-end">
            <DisclosureButton class=" font-black text-red-600 hover:underline">
                <small>{{ open ? 'Mostrar menos' : 'Mostrar más' }}</small>
            </DisclosureButton>
        </div>
    </Disclosure>
    <div v-else class="ck-content-output" :class="contentClass" v-html="content" />
</template>

<style scoped></style>