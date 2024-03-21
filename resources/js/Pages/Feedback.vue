<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    content: null,
    email: null,
})

function submit() {
    form.post(route('feedback.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    })
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Feedback
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <form @submit.prevent="submit">
                                <h1 class="text-2xl font-medium text-gray-900">New feedback</h1>
                                <div class="mt-2">
                                    <textarea rows="4" name="content" id="content" v-model="form.content" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <span v-if="form.errors.content" class="text-red-500">{{ form.errors.content }}</span>
                                </div>
                                <div v-if="!$page.props.auth.user" class="mt-2">
                                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                    <div class="mt-2">
                                        <input type="email" name="email" id="email" v-model="form.email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="you@example.com" />
                                        <span v-if="form.errors.email" class="text-red-500">{{ form.errors.email }}</span>
                                    </div>
                                </div>
                                <span v-if="$page.props.jetstream.flash" class="text-red-500">{{ $page.props.jetstream.flash.message }}</span>
                                <div class="flex justify-end mt-4">
                                    <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        <span v-if="form.processing">Processing</span>
                                        <span v-else>Send</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
