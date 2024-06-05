<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import BalloonEditor from '@ckeditor/ckeditor5-build-balloon';
import TextInput from '@/Components/TextInput.vue';
import { ChevronDoubleUpIcon, PaperClipIcon, BookmarkIcon, XMarkIcon, TagIcon } from '@heroicons/vue/20/solid';
import { ArrowDownTrayIcon, DocumentDuplicateIcon } from '@heroicons/vue/24/outline';
import { ExclamationTriangleIcon } from '@heroicons/vue/24/solid';
import { isImage } from '@/functions';

const props = defineProps({
    errors: Object,
});

const attachments = ref([]);
const tags = ref([]);
const attachmentsErrors = ref([]);
const searchResults = ref([]);
const creandoPublicacion = ref(false);
const tagsText = ref('');
const alreadySearched = ref(null);
const formPost = useForm({
    id: null,
    title: '',
    body: '',
    attachments: [],
    tags: [],
});

const editor = BalloonEditor;
const editorConfig = {
    toolbar: ['heading', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'link', '|', 'blockQuote'],
    balloonToolbar: ['heading', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'link', '|', 'blockQuote'],
};


function submit() {
    formPost.attachments = attachments.value.map(upFile => upFile.file);
    formPost.tags = tags.value.map(tags => tags);
    formPost.post(route('post.create'), {
        preserveScroll: true,
        onSuccess: function () {
            resetFormAndImageInputs();
        },
        onError: function (errors) {
            attachmentsErrors.value.splice(0, attachmentsErrors.value.length);
            for (const key in errors) {
                if (!attachmentsErrors.value.includes(errors[key])) {
                    attachmentsErrors.value.push(errors[key]);
                }
            }
        }
    });
}

async function attachmentFiles(e) {
    if (e.target.files.length + attachments.value.length <= 6) {
        for (const file of e.target.files) {
            const upFile = {
                file,
                url: await readFile(file),
            };
            attachments.value.push(upFile);
        }
        e.target.value = null;
    } else {
        alert('El máximo de archivos que se pueden añadir a un post es de 6');
        e.target.value = null;
    }
}

async function readFile(file) {
    return new Promise((resolve, reject) => {
        if (isImage(file)) {
            const reader = new FileReader();
            reader.onload = function () {
                resolve(reader.result);
            }
            reader.onerror = reject;

            reader.readAsDataURL(file);
        } else {
            resolve(null);
        }
    })
}

function removeAttached(attachment, index) {
    attachments.value = attachments.value.filter(f => f !== attachment);
    attachmentsErrors.value.splice(index, 0);
}

function resetForm() {
    formPost.reset();
    attachments.value.splice(0, attachments.value.length);
    attachmentsErrors.value.splice(0, attachmentsErrors.value.length);
    tagsText.value = '';
    searchResults.value = [];
    alreadySearched.value = false;
    tags.value.splice(0, tags.value.length);
}

function resetFormAndImageInputs() {
    resetForm();
    creandoPublicacion.value = false;
}

async function searchTags() {
    if (tagsText.value.length > 2) {
        alreadySearched.value = true;
        try {
            const response = await axios.get(`/search/tags/${tagsText.value}`)
            searchResults.value = response.data;
        } catch (error) {
            console.error('Error fetching search results:', error);
        }
    } else {
        alreadySearched.value = false;
        searchResults.value = [];
    }
}

function addTag(item, item_type) {
    tags.value.push({
        'type': item_type,
        'tag_id': item.id,
        'name': item.name,
    });
    alreadySearched.value = false;
    searchResults.value = [];
    tagsText.value = '';

}

function removeTag(tag) {
    tags.value = tags.value.filter(f => f !== tag);
}

</script>

<template>
    <div class="bg-white rounded p-4 shadow mb-3 overflow-auto">
        <div v-if="creandoPublicacion && attachmentsErrors.length > 0"
            class="p-3 bg-red-400 text-gray-200 rounded-lg mb-3">
            <div class="flex items-center text-lg">
                <ExclamationTriangleIcon class="mr-2 size-8" />
                Error
            </div>
            <ul>
                <li v-for="error of attachmentsErrors">{{ error }}</li>
            </ul>
        </div>
        <div v-if="creandoPublicacion" class="flex justify-between">
            <p>
                Título (opcional)
            </p>
            <button @click="resetFormAndImageInputs" class="flex justify-center gap-2">
                <ChevronDoubleUpIcon class="size-6 -mr-[5px]" />
            </button>
        </div>
        <TextInput @click="creandoPublicacion = true"
            :placeholder="creandoPublicacion ? '' : 'Haz Click aquí para postear'" class="w-full"
            v-model="formPost.title" />
        <div v-if="creandoPublicacion" class="my-3">
            Contenido
            <ckeditor :editor="editor" v-model="formPost.body" :config="editorConfig">
            </ckeditor>
        </div>

        <!-- Busqueda de temas -->
        <div v-if="creandoPublicacion" class="mb-3">
            <div>
                <span>Temas (1 - 3) </span>
            </div>
            <div>
                <div class="w-full">
                    <TextInput @keyup="searchTags" placeholder="Buscar tema" class="w-full" v-model="tagsText" />
                </div>
                <div v-if="tags.length > 0" class="flex items-center">
                    <span>Temas añadidos: </span>
                    <div class="flex flex-wrap">
                        <button @click="removeTag(tag)" v-for="tag of tags"
                            class="flex items-center justify-center rounded-md bg-rose-600 p-1 pr-2 m-1 text-xs text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                            <XMarkIcon class="size-4 mr-1" />
                            <p>
                                {{ tag.name }}
                            </p>
                        </button>
                    </div>
                </div>
                <div v-if="searchResults && Object.keys(searchResults).length > 0" class="mt-2">
                    <div v-if="searchResults.games.length > 0">
                        <h2 class="text-2xl">Juegos</h2>
                        <div class="flex flex-row flex-wrap items-center justify-start">
                            <button @click="addTag(game, 'game')" v-for="game of searchResults.games"
                                class="flex items-center justify-center rounded-md bg-rose-600 p-1 pr-2 m-1 text-xs text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                                <TagIcon class="size-3 mr-1" />
                                {{ game.name }}
                            </button>
                        </div>
                    </div>
                    <div v-if="searchResults.genres.length > 0">
                        <h2 class="text-2xl">Géneros</h2>
                        <div class="flex flex-row flex-wrap items-center justify-start">
                            <button @click="addTag(genre, 'genre')" v-for="genre of searchResults.genres"
                                class="flex items-center justify-center rounded-md bg-rose-600 p-1 pr-2 m-1 text-xs text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                                <TagIcon class="size-3 mr-1" />
                                {{ genre.name }}
                            </button>
                        </div>
                    </div>
                    <div v-if="searchResults.platforms.length > 0">
                        <h2 class="text-2xl">Plataformas</h2>
                        <div class="flex flex-row flex-wrap items-center justify-start">
                            <button @click="addTag(platform, 'platform')" v-for="platform of searchResults.platforms"
                                class="flex items-center justify-center rounded-md bg-rose-600 p-1 pr-2 m-1 text-xs text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                                <TagIcon class="size-3 mr-1" />
                                {{ platform.name }}
                            </button>
                        </div>
                    </div>
                    <div v-if="searchResults.developers.length > 0">
                        <h2 class="text-2xl">Desarrolladores</h2>
                        <div class="flex flex-row flex-wrap items-center justify-start">
                            <button @click="addTag(developer, 'developer')"
                                v-for="developer of searchResults.developers"
                                class="flex items-center justify-center rounded-md bg-rose-600 p-1 pr-2 m-1 text-xs text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                                <TagIcon class="size-3 mr-1" />
                                {{ developer.name }}
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="searchResults && Object.keys(searchResults).length == 0 && alreadySearched"
                    class=" text-center">
                    No hay resultados
                </div>
                <!-- <pre>{{ searchResults }}</pre> -->
            </div>
        </div>


        <div v-if="creandoPublicacion && attachments" class="grid gap-4 mb-3" :class="[
            attachments.length === 1 ? 'grid-cols-1' : '',
            attachments.length === 2 ? 'grid-cols-2' : '',
            attachments.length >= 3 ? 'grid-cols-2 md:grid-cols-3' : '',
        ]">
            <div v-for="(attachment, index) of attachments" class="relative">
                <div
                    class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 text-center break-words overflow-hidden relative">
                    <button @click="removeAttached(attachment, index)"
                        class="absolute right-2 top-2 bg-black/40 hover:bg-black/80 p-1 rounded-full flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-all">
                        <!-- descarga -->
                        <XMarkIcon class="size-5 text-white" />
                    </button>
                    <img v-if="isImage(attachment.file)" :src="attachment.url" class="object-contain aspect-square">
                    <div v-else
                        class="aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 px-2">
                        <!-- archivo/fichero -->
                        <DocumentDuplicateIcon class=" max-w-12 max-h-12 mb-2" />
                        <small>{{ attachment.file.name }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="creandoPublicacion" class="flex gap-2 justify-end">
            <button type="button"
                class="flex relative items-center justify-center rounded-md bg-rose-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600 cursor-pointer">
                <PaperClipIcon class="size-5 mr-1" />
                Añadir imagen
                <input @change="attachmentFiles" type="file" multiple
                    class=" absolute left-0 top-0 right-0 bottom-0 opacity-0" />
            </button>

            <button @click="submit" type="submit"
                class="flex items-center justify-center rounded-md bg-rose-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                <BookmarkIcon class="size-5 mr-1" />
                Publicar
            </button>
        </div>
    </div>
</template>

<style scoped></style>