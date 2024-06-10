<script setup>
import { ref } from 'vue';
import TagItem from './TagItem.vue';

const foundGame = ref(false);
const foundGenre = ref(false);
const foundPlatform = ref(false);
const foundDeveloper = ref(false);

defineProps({
    tagsFollowing: Object,
});

</script>

<template>
    <div class="mt-5 max-h-[200px] md:max-h-full md:flex-1 overflow-auto">
        <div v-if="tagsFollowing.data.length > 0" class="text-gray-600 text-center p-1">
            <template v-for="tag of tagsFollowing.data">
                <template v-if="tag.type == 'game' && !foundGame">
                    {{ foundGame = true }}
                </template>
                <template v-if="tag.type == 'genre' && !foundGenre">
                    {{ foundGenre = true }}
                </template>
                <template v-if="tag.type == 'platform' && !foundPlatform">
                    {{ foundPlatform = true }}
                </template>
                <template v-if="tag.type == 'developer' && !foundDeveloper">
                    {{ foundDeveloper = true }}
                </template>
            </template>

            <div>
                <div v-if="foundGame">
                    <h3 class="text-xl text-left">Juegos</h3>
                    <div class="flex flex-wrap gap-2">
                        <template v-for="tag of tagsFollowing.data">
                            <TagItem v-if="tag.type == 'game'" :tag="tag" class="col-span-12 md:col-span-6" />
                        </template>
                    </div>
                </div>
                <div v-if="foundGenre">
                    <h3 class="text-xl text-left">Géneros</h3>
                    <div class="flex flex-wrap gap-2">
                        <template v-for="tag of tagsFollowing.data">
                            <TagItem v-if="tag.type == 'genre'" :tag="tag" class="col-span-12 md:col-span-6" />
                        </template>
                    </div>
                </div>
                <div v-if="foundPlatform">
                    <h3 class="text-xl text-left">Plataformas</h3>
                    <div class="flex flex-wrap gap-2">
                        <template v-for="tag of tagsFollowing.data">
                            <TagItem v-if="tag.type == 'platform'" :tag="tag" class="col-span-12 md:col-span-6" />
                        </template>
                    </div>
                </div>
                <div v-if="foundDeveloper">
                    <h3 class="text-xl text-left">Desarrolladores</h3>
                    <div class="flex flex-wrap gap-2">
                        <template v-for="tag of tagsFollowing.data">
                            <TagItem v-if="tag.type == 'developer'" :tag="tag" class="col-span-12 md:col-span-6" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            No tienes ningún tema añadido a Favoritos
        </div>
    </div>
</template>

<style scoped></style>