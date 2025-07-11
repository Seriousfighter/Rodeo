<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-cow text-green-600 mr-3"></i>
                            {{ isEditing ? 'Editar Animal' : 'Nuevo Animal' }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ isEditing ? 'Actualiza la información del animal' : 'Registra un nuevo animal en el sistema de control reproductivo' }}
                        </p>
                    </div>
                    <Link 
                        :href="route('animals.index')"
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
                            <i class="fas fa-cow text-green-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Información del Animal</h2>
                            <p class="text-sm text-gray-600">Complete todos los campos requeridos</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Caravana Field -->
                        <div>
                            <label for="caravana" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag text-gray-400 mr-1"></i>
                                Caravana *
                            </label>
                            <input
                                id="caravana"
                                v-model="form.caravana"
                                type="text"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.caravana }"
                                placeholder="Ingrese el número de caravana"
                            />
                            <p v-if="errors.caravana" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.caravana }}
                            </p>
                        </div>

                        <!-- Caravana Oficial Field -->
                        <div>
                            <label for="caravana_oficial" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-certificate text-gray-400 mr-1"></i>
                                Caravana Oficial
                            </label>
                            <input
                                id="caravana_oficial"
                                v-model="form.caravana_oficial"
                                type="text"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.caravana_oficial }"
                                placeholder="Ingrese la caravana oficial (opcional)"
                            />
                            <p v-if="errors.caravana_oficial" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.caravana_oficial }}
                            </p>
                        </div>

                        <!-- Rodeo Field -->
                        <div class="md:col-span-2">
                            <label for="rodeo_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clipboard-list text-gray-400 mr-1"></i>
                                Rodeo *
                            </label>
                            <select
                                id="rodeo_id"
                                v-model="form.rodeo_id"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.rodeo_id }"
                            >
                                <option value="">Seleccione un rodeo</option>
                                <option 
                                    v-for="rodeo in rodeos" 
                                    :key="rodeo.id" 
                                    :value="rodeo.id"
                                >
                                    {{ rodeo.name }} - {{ rodeo.client?.name }} {{ rodeo.client?.last_name }}
                                </option>
                            </select>
                            <p v-if="errors.rodeo_id" class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ errors.rodeo_id }}
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
                                        Este animal será registrado en el sistema de control reproductivo bovino. 
                                        La caravana debe ser única dentro del rodeo seleccionado.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <Link 
                            :href="route('animals.index')"
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
                            {{ processing ? 'Procesando...' : (isEditing ? 'Actualizar Animal' : 'Crear Animal') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Animal Preview Card (Only for editing) -->
            <div v-if="isEditing && animal" class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                    Vista Previa del Animal
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-tag text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Caravana:</strong> {{ form.caravana }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-certificate text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Caravana Oficial:</strong> {{ form.caravana_oficial || 'No especificada' }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clipboard-list text-gray-400"></i>
                        <span class="text-sm text-gray-600">
                            <strong>Rodeo:</strong> {{ getRodeoName(form.rodeo_id) }}
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
    animal: {
        type: Object,
        default: null
    },
    rodeos: {
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
const isEditing = computed(() => props.animal !== null)

// Form data using Inertia's useForm
const form = useForm({
    caravana: props.animal?.caravana ?? '',
    caravana_oficial: props.animal?.caravana_oficial ?? '',
    rodeo_id: props.animal?.rodeo_id ?? ''
})

// Methods
const getRodeoName = (rodeoId) => {
    if (!rodeoId) return 'No seleccionado'
    const rodeo = props.rodeos.find(r => r.id == rodeoId)
    return rodeo ? `${rodeo.name} - ${rodeo.client?.name} ${rodeo.client?.last_name}` : 'Rodeo no encontrado'
}

const submitForm = () => {
    processing.value = true
    
    if (isEditing.value) {
        // Update existing animal
        form.put(route('animals.update', props.animal.id), {
            onFinish: () => {
                processing.value = false
            }
        })
    } else {
        // Create new animal
        form.post(route('animals.store'), {
            onFinish: () => {
                processing.value = false
            }
        })
    }
}

// Lifecycle
onMounted(() => {
    // Focus on first input when component mounts
    document.getElementById('caravana')?.focus()
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