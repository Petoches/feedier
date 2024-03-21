<script setup>
import {XMarkIcon, ArrowPathIcon} from "@heroicons/vue/24/solid";

const emit = defineEmits(['delete', 'restore']);

const props = defineProps({
    'feedback': {
        type: Object,
        default: {}
    },
    'context': {
        type: String,
        default: 'feedback'
    }
})

</script>

<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col" class="text-start text-sm font-semibold text-gray-900">Email</th>
              <th scope="col" class="text-start text-sm font-semibold text-gray-900">Content</th>
              <th scope="col" class="text-start text-sm font-semibold text-gray-900">Source</th>
              <th scope="col" class="text-start text-sm font-semibold text-gray-900">Date</th>
              <th scope="col" class="text-start text-sm relative py-3.5 pl-3 pr-4 sm:pr-0">
              </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            <tr v-for="feedback in props.feedback" :key="feedback.id">
              <td class="whitespace-nowrap text-sm font-medium text-gray-900 sm:pl-0">{{ feedback.email }}</td>
              <td class="whitespace-nowrap text-sm text-gray-500">{{ feedback.content }}</td>
              <td class="whitespace-nowrap text-sm text-gray-500">{{ feedback.source }}</td>
              <td class="whitespace-nowrap text-sm text-gray-500">{{ feedback.created_at }}</td>
              <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                <XMarkIcon v-if="props.context === 'feedback'" class="h-6 w-6 text-red-500 cursor-pointer" @click="emit('delete', feedback.id)"/>
                <ArrowPathIcon v-else-if="props.context === 'archived'" class="h-6 w-6 text-blue-500 cursor-pointer" @click="emit('restore', feedback.id)"/>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
