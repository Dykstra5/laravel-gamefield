<script setup>
    import { ref } from 'vue';

    const creandoPublicacion = ref(false);

</script>

<template>
    <div class="bg-white rounded p-4 shadow mb-3">
        <div @click="creandoPublicacion = true" class=" py-3 px-2 text-gray-400 border border-gray-200 rounded mb-3">
            Haz Click aquí para crear una nueva publicación
        </div>
        <div v-if="creandoPublicacion" class="flex gap-2 justify-between">
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 relative">Añadir archivo
                <input type="file" class=" absolute left-0 top-0 right-0 bottom-0 opacity-0" />
            </button>

            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Publicar</button>
        </div>
    </div>
</template>

<style scoped></style>