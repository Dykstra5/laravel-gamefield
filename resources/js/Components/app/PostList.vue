<script setup>
import PostItem from '@/Components/app/PostItem.vue';
import AttachmentsModal from '@/Components/app/AttachmentsModal.vue';
import { ref } from 'vue';

defineProps({
    posts: Array,
})

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
    <div class="overflow-auto">
        <PostItem v-for="post of posts" :key="post.post_id" :post="post" @attachmentClick="openAttachmentsModal" />
        <AttachmentsModal :attachments="postAttachments.post?.attachments || []"
            v-model:attachment_index="postAttachments.attachment_index" 
            v-model="showAttachmentsModal" />
    </div>
</template>

<style scoped></style>