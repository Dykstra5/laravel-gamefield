<script setup>
import PostItem from '@/Components/app/PostItem.vue';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AttachmentsModal from '@/Components/app/AttachmentsModal.vue';


defineProps({
    post: Object,
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
        <div class="lg:grid lg:grid-cols-12 container mx-auto mt-4 overflow-auto">
            <PostItem :post="post.data" class="lg:col-start-4 lg:col-end-10" @attachmentClick="openAttachmentsModal" />
        </div>
        <AttachmentsModal :attachments="postAttachments.post?.attachments || []"
                v-model:attachment_index="postAttachments.attachment_index" v-model="showAttachmentsModal" />
    </AuthenticatedLayout>
</template>
