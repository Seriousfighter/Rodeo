<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-user-plus text-green-600 mr-3"></i>
                            {{ isEditing ? 'Editar Ganadero' : 'Nuevo Ganadero' }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ isEditing ? 'Actualiza la información del cliente' : 'Registra un nuevo cliente para el control reproductivo bovino' }}
                        </p>
                    </div>
                    <Link 
                        :href="route('clients.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </Link>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Información del Cliente</h2>
                            <p class="text-sm text-gray-600">Complete todos los campos requeridos</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user text-gray-400 mr-1"></i>
                                Nombre *
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.name }"
                                placeholder="Ingrese el nombre"
                            />
                            <p v-if="errors.name" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.name }}
                            </p>
                        </div>

                        <!-- Last Name Field -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user text-gray-400 mr-1"></i>
                                Apellido *
                            </label>
                            <input
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.last_name }"
                                placeholder="Ingrese el apellido"
                            />
                            <p v-if="errors.last_name" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.last_name }}
                            </p>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                Correo Electrónico *
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.email }"
                                placeholder="correo@ejemplo.com"
                            />
                            <p v-if="errors.email" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.email }}
                            </p>
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone text-gray-400 mr-1"></i>
                                Teléfono *
                            </label>
                            <input
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.phone }"
                                placeholder="+57 300 123 4567"
                            />
                            <p v-if="errors.phone" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.phone }}
                            </p>
                        </div>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-blue-900">Información Importante</h3>
                                    <p class="text-sm text-blue-700 mt-1">
                                        Este cliente será registrado en el sistema de control reproductivo bovino. 
                                        Asegúrese de que toda la información de contacto esté actualizada.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <Link 
                            :href="route('clients.index')"
                            class="inline-flex justify-center items-center px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </Link>
                        
                        <button
                            type="submit"
                            :disabled="processing"
                            class="inline-flex justify-center items-center px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm"
                        >
                            <i v-if="processing" class="fas fa-spinner fa-spin mr-2"></i>
                            <i v-else :class="isEditing ? 'fas fa-save' : 'fas fa-plus'" class="mr-2"></i>
                            {{ processing ? 'Procesando...' : (isEditing ? 'Actualizar Cliente' : 'Crear Cliente') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Client Preview Card (Only for editing) -->
            <div v-if="isEditing && client" class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                    Vista Previa del Cliente
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Nombre:</strong> {{ form.name }} {{ form.last_name }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Email:</strong> {{ form.email }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Teléfono:</strong> {{ form.phone }}
                        </span>
                    </div>
                    <div v-if="client.rodeos" class="flex items-center space-x-3">
                        <i class="fas fa-clipboard-list text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Rodeos:</strong> {{ client.rodeos.length }} registrados
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'

// Props
const props = defineProps({
    client: {
        type: Object,
        default: null
    },
    errors: {
        type: Object,
        default: () => ({})
    }
})

// Reactive data
const processing = ref(false)

// Computed properties
const isEditing = computed(() => props.client !== null)

// Form data using Inertia's useForm
const form = useForm({
    name: props.client?.name ?? '',
    last_name: props.client?.last_name ?? '',
    email: props.client?.email ?? '',
    phone: props.client?.phone ?? ''
})

// Methods
const submitForm = () => {
    processing.value = true
    
    if (isEditing.value) {
        // Update existing client
        form.put(route('clients.update', props.client.id), {
            onFinish: () => {
                processing.value = false
            },
            onSuccess: () => {
                // Handle success if needed
            },
            onError: () => {
                // Handle errors if needed
            }
        })
    } else {
        // Create new client
        form.post(route('clients.store'), {
            onFinish: () => {
                processing.value = false
            },
            onSuccess: () => {
                // Handle success if needed
            },
            onError: () => {
                // Handle errors if needed
            }
        })
    }
}

// Lifecycle
onMounted(() => {
    // Focus on first input when component mounts
    document.getElementById('name')?.focus()
})
</script>

<style scoped>
/* Custom styles for form validation */
.transition-colors {
    transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

/* Loading spinner animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.fa-spin {
    animation: spin 1s linear infinite;
}
</style>