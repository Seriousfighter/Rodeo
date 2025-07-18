<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex items-center space-x-3 mb-4">
                <i :class="iconClass" class="text-2xl"></i>
                <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
            </div>
            <p class="text-gray-600 mb-6" v-html="message"></p>
            <div class="flex space-x-3">
                <button
                    @click="$emit('cancel')"
                    class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                >
                    {{ cancelText }}
                </button>
                <button
                    @click="$emit('confirm')"
                    :class="confirmButtonClass"
                    class="flex-1 px-4 py-2 font-medium rounded-lg transition-colors duration-200"
                >
                    {{ confirmText }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

// Props
const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Confirmar Acción'
    },
    message: {
        type: String,
        required: true
    },
    confirmText: {
        type: String,
        default: 'Confirmar'
    },
    cancelText: {
        type: String,
        default: 'Cancelar'
    },
    type: {
        type: String,
        default: 'danger', // danger, warning, info, success
        validator: (value) => ['danger', 'warning', 'info', 'success'].includes(value)
    }
})

// Emits
defineEmits(['confirm', 'cancel'])

// Computed properties
const iconClass = computed(() => {
    const baseClass = 'fas text-2xl'
    switch (props.type) {
        case 'danger':
            return `${baseClass} fa-exclamation-triangle text-red-600`
        case 'warning':
            return `${baseClass} fa-exclamation-circle text-yellow-600`
        case 'info':
            return `${baseClass} fa-info-circle text-blue-600`
        case 'success':
            return `${baseClass} fa-check-circle text-green-600`
        default:
            return `${baseClass} fa-exclamation-triangle text-red-600`
    }
})

const confirmButtonClass = computed(() => {
    switch (props.type) {
        case 'danger':
            return 'bg-red-600 hover:bg-red-700 text-white'
        case 'warning':
            return 'bg-yellow-600 hover:bg-yellow-700 text-white'
        case 'info':
            return 'bg-blue-600 hover:bg-blue-700 text-white'
        case 'success':
            return 'bg-green-600 hover:bg-green-700 text-white'
        default:
            return 'bg-red-600 hover:bg-red-700 text-white'
    }
})
</script>