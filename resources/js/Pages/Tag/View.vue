<script setup>
import PostItem from '@/Components/app/PostItem.vue';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AttachmentsModal from '@/Components/app/AttachmentsModal.vue';


defineProps({
    posts: Object,
});

const showAttachmentsModal = ref(false);
const postAttachments = ref({});

function openAttachmentsModal(post, attachment_index) {
    postAttachments.value = {
        post,
        attachment_index
    };
    showAttachmentsModal.value = true;
}

</script>

<template>
    <AuthenticatedLayout>
        <pre>{{ posts }}</pre>
    </AuthenticatedLayout>
</template>
