<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    game: {
        type: Object
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="container mx-auto px-4 h-full overflow-auto">
            <div class="bg-white rounded-b-lg">
                <img :src="game.data.image_background || '/img/default-cover-red.png'" alt="jajasi"
                    class="w-full h-[500px] object-cover bg-[#922828]">
                <div class="flex">
                    <div class="flex items-center justify-between flex-1 p-3">
                        <h2 class="font-black text-4xl">{{ game.data.name }}</h2>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg mt-4 p-4">
                <h2 class=" text-2xl font-black">Información</h2>
                <div>
                    <div class=" flex items-center font-bold">
                        <p>Título:</p>
                        <p class="text-xl ml-2">{{ game.data.name }}</p>
                    </div>
                    <div class=" flex items-center font-bold">
                        <p>Nota en Metacritic:</p>
                        <p class="text-xl ml-2">{{ game.data.metacritic }}</p>
                    </div>
                    <div class=" flex items-center font-bold">
                        <p>Fecha de Lanzamiento:</p>
                        <p class="text-xl ml-2">{{ game.data.released }}</p>
                    </div>
                    <div class=" flex items-center font-bold">
                        <p>Horas de Juego:</p>
                        <p class="text-xl ml-2">{{ game.data.playtime }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
