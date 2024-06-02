<script setup>
import { computed, ref } from 'vue';
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue';
import { ChevronRightIcon, ChevronLeftIcon, XMarkIcon } from '@heroicons/vue/24/outline'


const props = defineProps({
    attachments: {
        type: Array,
        required: true
    },
    attachment_index: Number,
    modelValue: Boolean
});


const emit = defineEmits(['update:modelValue', 'update:attachment_index', 'hide']);

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const index = computed({
    get: () => props.attachment_index,
    set: (value) => emit('update:attachment_index', value)
});

const attachmentsLength = computed({
    get: () => props.attachments.length,
    set: (value) => value
});

function closeModal() {
    show.value = false;
    emit('hide');
}

function prev() {
    if (index.value > 0) {
        index.value--;
    } else {
        index.value = attachmentsLength.value - 1;
    }
}

function next() {
    if (index.value < attachmentsLength.value - 1) {
        index.value++;
    } else {
        index.value = 0;
    }
}
</script>

<template>
    <teleport to="body">
        <TransitionRoot appear :show="show" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-10">
                <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                    enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-black/25" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="h-screen w-screen relative">
                        <TransitionChild as="template" class="w-full h-full" enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95">
                            <DialogPanel
                                class=" flex flex-col w-full transform overflow-hidden bg-white md:p-6 text-left align-middle shadow-xl transition-all">
                                <button type="button"
                                    class="absolute right-5 top-5 z-30 inline-flex justify-center"
                                    @click="closeModal">
                                    <XMarkIcon class="size-8" />
                                </button>
                                <div class="flex items-center justify-between h-full w-full group">
                                    <button v-if="attachmentsLength > 1" @click="prev" class="md:mx-5 flex justify-center gap-2">
                                        <ChevronLeftIcon class="size-16" />
                                    </button>
                                    <div class=" flex items-center justify-center w-full h-full">
                                        <img :src="attachments[index].url" :alt="attachments[index].name"
                                        class="max-w-full max-h-full">
                                    </div>
                                    <button v-if="attachmentsLength > 1" @click="next" class="md:mx-5 flex justify-center gap-2">
                                        <ChevronRightIcon class="size-16" />
                                    </button>
                                </div>
                                <div
                                    class="flex items-center justify-center absolute h-10 right-0 left-0 bottom-0 text-white bg-black/60 opacity-100 group-hover:opacity-100 transition-all">
                                    {{ index + 1 }}/{{ attachmentsLength }}
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>
