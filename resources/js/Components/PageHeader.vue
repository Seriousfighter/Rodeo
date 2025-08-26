<script setup>
import Breadcrumb from '@/Components/Breadcrumb.vue';

defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: null
    },
    breadcrumbs: {
        type: Array,
        default: () => []
    },
    showActions: {
        type: Boolean,
        default: true
    }
});
</script>

<template>
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Breadcrumbs -->
            <div v-if="breadcrumbs.length > 0" class="mb-4">
                <Breadcrumb :items="breadcrumbs" />
            </div>

            <!-- Header Content -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
                    <p v-if="subtitle" class="mt-1 text-sm text-gray-600">{{ subtitle }}</p>
                </div>

                <!-- Action Buttons -->
                <div v-if="showActions && $slots.actions" class="mt-4 sm:mt-0 sm:ml-4">
                    <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                        <slot name="actions" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>