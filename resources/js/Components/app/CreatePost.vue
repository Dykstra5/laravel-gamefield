<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import BalloonEditor from '@ckeditor/ckeditor5-build-balloon';
import TextInput from '@/Components/TextInput.vue';
import { ChevronDoubleUpIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    errors: Object,
});

const editor = BalloonEditor;
const editorConfig = {
    toolbar: ['heading', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'link', '|', 'blockQuote'],
    balloonToolbar: ['heading', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'link', '|', 'blockQuote'],
};

const creandoPublicacion = ref(false);
const formPost = useForm({
    title: '',
    body: '',
});

function submit() {
    formPost.post(route('post.create'), {
        preserveScroll: true,
        onSuccess: function () {
            formPost.reset();
        },
    });
}

</script>

<template>
    <pre>{{ errors }}</pre>
    <div class="bg-white rounded p-4 shadow mb-3">
        <div v-if="creandoPublicacion" class="flex justify-between">
            <p>
                Titulo (opcional)
            </p>
            <button @click="creandoPublicacion = false" class="flex justify-center gap-2">
                <ChevronDoubleUpIcon class="size-6" />
            </button>
        </div>
        <TextInput @click="creandoPublicacion = true" placeholder="Haz Click aquí para postear"
            class="w-full" v-model="formPost.title" />
        <div v-if="creandoPublicacion" class="my-3">
            Contenido
            <ckeditor :editor="editor" v-model="formPost.body" :config="editorConfig">
            </ckeditor>
        </div>
        <div v-if="creandoPublicacion" class="mb-3">
            Tags
        </div>
        <div v-if="creandoPublicacion" class="flex gap-2 justify-between">
            <button type="button"
                class="rounded-md bg-rose-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600 relative">
                Añadir archivo
                <input type="file" class=" absolute left-0 top-0 right-0 bottom-0 opacity-0" />
            </button>

            <button @click="submit" type="submit"
                class="rounded-md bg-rose-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                Publicar
            </button>
        </div>
    </div>
</template>

<style scoped></style>