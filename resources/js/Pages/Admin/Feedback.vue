<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from '@inertiajs/vue3'
import FeedbackTable from "@/Pages/Admin/FeedbackTable.vue";

const props = defineProps({
    'feedback': {
        type: Object,
        default: {}
    },
    'archivedFeedback': {
        type: Object,
        default: {}
    }
})

function deleteFeedback(feedbackId) {
    const form = useForm({
        feedbackId: feedbackId,
    });

    form.delete(route('feedback.delete'));
}

function restoreFeedback(feedbackId) {
    const form = useForm({
        feedbackId: feedbackId,
    });

    form.patch(route('feedback.restore'));
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin/Feedback
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <FeedbackTable :feedback="props.feedback" @delete="deleteFeedback" />
                            <div v-if="Object.keys(props.archivedFeedback).length > 0" >
                                <h3 class="text-base font-semibold leading-7 border-b border-gray-100 mt-6">Archived</h3>
                                <FeedbackTable :feedback="props.archivedFeedback" context="archived" @restore="restoreFeedback" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
