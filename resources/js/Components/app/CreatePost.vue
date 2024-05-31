<script setup>
import { ref } from 'vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import { useForm } from '@inertiajs/vue3';

const creandoPublicacion = ref(false);
const formPost = useForm({
    body: '',
});

function submit() {
    formPost.post(route('post.create'),{
        onSuccess: function () {
            formPost.reset();
        }
    });
}

</script>

<template>
    <div class="bg-white rounded p-4 shadow mb-3">
        <TextAreaInput @click="creandoPublicacion = true" placeholder="Haz Click aquí para crear una nueva publicación"
            class="mb-3 w-full" v-model="formPost.body" />
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