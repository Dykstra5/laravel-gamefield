<script setup>
import GroupItem from '@/Components/app/GroupItem.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const searchKeyword = ref('');

</script>

<template>
    <TextInput :model="searchKeyword" placeholder="Buscar..." class=" w-full mt-4" />
    <div class="mt-5 h-[200px] md:h-full md:flex-1 overflow-auto">
        <div v-if="false" class=" text-gray-600 text-center p-5">
            No tienes temas favoritos
        </div>
        <div v-else>
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Laravel Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
            <GroupItem image="https://picsum.photos/100" title="Vue.js Developers"
                description="Lorem, ipsum dolor sit amet consectetur beatae, totam cum corporis quae?" />
        </div>
    </div>
</template>

<style scoped></style>