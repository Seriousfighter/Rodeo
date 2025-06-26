<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-clipboard-list text-green-600 mr-3"></i>
                            {{ isEditing ? 'Editar Rodeo' : 'Nuevo Rodeo' }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ isEditing ? 'Actualiza la información del rodeo' : 'Registra un nuevo rodeo para el control reproductivo bovino' }}
                        </p>
                    </div>
                    <Link 
                        :href="route('rodeos.index')"
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
                            <i class="fas fa-clipboard-list text-green-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Información del Rodeo</h2>
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
                                <i class="fas fa-tag text-gray-400 mr-1"></i>
                                Nombre del Rodeo *
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.name }"
                                placeholder="Ingrese el nombre del rodeo"
                            />
                            <p v-if="errors.name" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.name }}
                            </p>
                        </div>

                        <!-- Client Field -->
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user text-gray-400 mr-1"></i>
                                Cliente *
                            </label>
                            <select
                                id="client_id"
                                v-model="form.client_id"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.client_id }"
                            >
                                <option value="">Seleccione un cliente</option>
                                <option 
                                    v-for="client in clients" 
                                    :key="client.id" 
                                    :value="client.id"
                                >
                                    {{ client.name }} {{ client.last_name }}
                                </option>
                            </select>
                            <p v-if="errors.client_id" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.client_id }}
                            </p>
                        </div>

                        <!-- Location Field -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                Ubicación
                            </label>
                            <input
                                id="location"
                                v-model="form.location"
                                type="text"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.location }"
                                placeholder="Ingrese la ubicación"
                            />
                            <p v-if="errors.location" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.location }}
                            </p>
                        </div>



                        <!-- RENSPA Field -->
                        <div class="md:col-span-2">
                            <label for="renspa" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-file-alt text-gray-400 mr-1"></i>
                                RENSPA
                            </label>
                            <input
                                id="renspa"
                                v-model="form.renspa"
                                type="text"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.renspa }"
                                placeholder="Ingrese el número RENSPA"
                            />
                            <p v-if="errors.renspa" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.renspa }}
                            </p>
                        </div>

                        <!-- Description Field -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left text-gray-400 mr-1"></i>
                                Descripción
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.description }"
                                placeholder="Ingrese una descripción del rodeo"
                            ></textarea>
                            <p v-if="errors.description" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.description }}
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
                                        Este rodeo será registrado en el sistema de control reproductivo bovino. 
                                        Después de crear el rodeo podrá agregar animales al mismo.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <Link 
                            :href="route('rodeos.index')"
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
                            {{ processing ? 'Procesando...' : (isEditing ? 'Actualizar Rodeo' : 'Crear Rodeo') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rodeo Preview Card (Only for editing) -->
            <div v-if="isEditing && rodeo" class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                    Vista Previa del Rodeo
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-tag text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Nombre:</strong> {{ form.name }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Cliente:</strong> {{ getClientName(form.client_id) }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Ubicación:</strong> {{ form.location || 'No especificada' }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-calendar text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Fecha:</strong> {{ form.date || 'No especificada' }}
                        </span>
                    </div>
                    <div v-if="rodeo.animals" class="flex items-center space-x-3">
                        <i class="fas fa-cow text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Animales:</strong> {{ rodeo.animals.length }} registrados
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
    rodeo: {
        type: Object,
        default: null
    },
    clients: {
        type: Array,
        default: () => []
    },
    errors: {
        type: Object,
        default: () => ({})
    }
})

// Reactive data
const processing = ref(false)

// Computed properties
const isEditing = computed(() => props.rodeo !== null)

// Form data using Inertia's useForm
const form = useForm({
    name: props.rodeo?.name ?? '',
    location: props.rodeo?.location ?? '',
    description: props.rodeo?.description ?? '',
    renspa: props.rodeo?.renspa ?? '',
    client_id: props.rodeo?.client_id ?? ''
})

// Methods
const getClientName = (clientId) => {
    if (!clientId) return 'No seleccionado'
    const client = props.clients.find(c => c.id == clientId)
    return client ? `${client.name} ${client.last_name}` : 'Cliente no encontrado'
}

const submitForm = () => {
    processing.value = true
    
    if (isEditing.value) {
        // Update existing rodeo
        form.put(route('rodeos.update', props.rodeo.id), {
            onFinish: () => {
                processing.value = false
            }
        })
    } else {
        // Create new rodeo
        form.post(route('rodeos.store'), {
            onFinish: () => {
                processing.value = false
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
.transition-colors {
    transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.fa-spin {
    animation: spin 1s linear infinite;
}
</style>