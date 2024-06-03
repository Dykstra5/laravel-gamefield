<script setup>
import PostItem from '@/Components/app/PostItem.vue';
import AttachmentsModal from '@/Components/app/AttachmentsModal.vue';
import { nextTick, onMounted, onUpdated, ref } from 'vue';
import axiosClient from '@/axiosClient';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const showAttachmentsModal = ref(false);
const postAttachments = ref({});
const loadMoreIntersect = ref(null);

const props = defineProps({
    posts: Array,
})

const allPosts = ref({
    data: page.props.posts.data,
    next: page.props.posts.links.next
})

function openAttachmentsModal(post, attachment_index) {
    postAttachments.value = {
        post,
        attachment_index
    };
    showAttachmentsModal.value = true;
}

function loadMore() {
    console.log('Entra aquí');
    if (allPosts.value.next) {
        axiosClient.get(allPosts.value.next)
            .then(({ data }) => {
                allPosts.value.data = [...allPosts.value.data, ...data.data];
                allPosts.value.next = data.links.next;
                console.log(allPosts.value.next,  data.links.next);
            })
    } else {
        return false;
    }

}

onMounted(() => {
    const observer = new IntersectionObserver((entries) => entries.forEach(entry => entry.isIntersecting && loadMore()), {
        rootMargin: '-100px 0px 0px 0px'
    })

    observer.observe(loadMoreIntersect.value)
});

</script>

<template>
    <div class="overflow-auto">
        <PostItem v-for="post of allPosts.data" :key="post.post_id" :post="post"
            @attachmentClick="openAttachmentsModal" />

        <div ref="loadMoreIntersect" class="h-[1px]"></div>

        <AttachmentsModal :attachments="postAttachments.post?.attachments || []"
            v-model:attachment_index="postAttachments.attachment_index" v-model="showAttachmentsModal" />
    </div>
</template>

<style scoped></style>